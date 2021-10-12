import React, { useEffect, useState } from 'react';
import { useHistory, useParams} from 'react-router-dom';
import { useSelector, useDispatch } from 'react-redux';
import ProfileImageWithDefault from './ProfileImageWithDefault';
import { useTranslation } from 'react-i18next';
import Input from './Input';
import { deleteUserAddRequest, updateUser, cancelDeleteUser} from '../api/apiCalls';
import { useApiProgress } from '../shared/ApiProgress';
import ButtonWithProgress from './ButtonWithProgress';
import { logoutSuccess, updateSuccess } from '../redux/authActions';
import Modal from './Modal';

const ProfileCard = props => {
    const [inEditMode, setInEditMode] = useState(false);
    const [updatedFullname, setUpdatedFullname] = useState();
    const [updatedEmail, setUpdatedEmail] = useState();
    const [updatedPhone, setUpdatedPhone] = useState();
    const { username: loggedInUsername, role } = useSelector((store) => ({username: store.username}));
    const routeParams = useParams();
    const pathUsername = routeParams.username;
    const [user, setUser] = useState({});
    const [deleteRequest, setDeleteRequest] = useState({});
    const [editable, setEditable] = useState(false);
    const [validationErrors, setValidationErrors] = useState({});
    const [modalVisible, setModalVisible] = useState(false);
    const dispatch = useDispatch();
    const history = useHistory();

    useEffect(() => {
        setUser(props.user);
    }, [props.user]);

    useEffect(() => {
        setDeleteRequest(props.deleteRequest);
    }, [props.deleteRequest]);

    useEffect(() => {
        setEditable(pathUsername === loggedInUsername);
    }, [pathUsername, loggedInUsername]);

    useEffect(() => {
        setValidationErrors((previousValidationErrors) => ({
            ...previousValidationErrors,
            fullname: null,
            email: null,
            phone: null
        }));
    }, [updatedFullname,updatedEmail, updatedPhone]);

    const { username, fullname, email, phone, created_at, updated_at } = user;

    const pendingApiCallDeleteUser = useApiProgress('delete',`/api/user/${username}`,true);

    const { t } = useTranslation();
    useEffect(() => {
        if(!inEditMode){
            setUpdatedFullname(null);
        }else{
            setUpdatedFullname(fullname);
        }
    }, [inEditMode, fullname]);

    useEffect(() => {
        if(!inEditMode){
            setUpdatedEmail(null);
        }else{
            setUpdatedEmail(email);
        }
    }, [inEditMode, email]);

    useEffect(() => {
        if(!inEditMode){
            setUpdatedPhone(null);
        }else{
            setUpdatedPhone(phone);
        }
    }, [inEditMode, phone]);

    const onClickSave = async () => {
        const body = {
            username,
            fullname: updatedFullname,
            email: updatedEmail,
            phone: updatedPhone,
            token: props.token
        };
        try{
            const response = await  updateUser(body);
            setInEditMode(false);
            setUser(response.data[0]);
            dispatch(updateSuccess(response.data));
        }catch(error){
            setValidationErrors(error.response.data.validationErrors);
        }

    }


    const onClickCancel = () => {
        setModalVisible(false);
    };

    const onClickDeleteUserRequest = async () => {
        const body = {
            username,
            token: props.token
        };
        await deleteUserAddRequest(body);
        setModalVisible(false);
        history.push('/');

    };

    const onClickCancelDeleteUser = async () => {
        const body = {
            username,
            token: props.token
        };
        await cancelDeleteUser(body);
        history.push('/');
    };

    const pendingApiCall = useApiProgress('put','/api/user/' + username);
    const { fullname: fullnameError, email: emailError, phone: phoneError} = validationErrors;

    return (
        <div className="card text-center">
            <div className="card text-center">
                <div className="card-header">
                    Profile
                </div>
                <div className="card-body">
                    {!inEditMode && (
                        <>
                            <h5 className="card-title">UserName:</h5>
                            <p className="card-text">{username}</p>
                            <h5 className="card-title">FullName:</h5>
                            <p className="card-text">{fullname}</p>
                            <h5 className="card-title">E-Mail:</h5>
                            <p className="card-text">{email}</p>
                            <h5 className="card-title">Phone:</h5>
                            <p className="card-text">{phone}</p>
                        </>
                    )}
                    {inEditMode && (
                        <div>
                            <Input
                                label={t('Change Full Name')}
                                defaultValue={fullname}
                                onChange={(event) => {setUpdatedFullname(event.target.value)}}
                                error={fullnameError}
                            />

                            <Input
                                label={t('Change E-Mail')}
                                defaultValue={email}
                                onChange={(event) => {setUpdatedEmail(event.target.value)}}
                                error={emailError}
                            />

                            <Input
                                label={t('Change Phone')}
                                defaultValue={phone}
                                onChange={(event) => {setUpdatedPhone(event.target.value)}}
                                error={phoneError}
                            />

                            <div className="m-2">
                                <ButtonWithProgress
                                    className="btn btn-primary d-inline-flex"
                                    onClick={onClickSave}
                                    disabled={pendingApiCall}
                                    pendingApiCall={pendingApiCall}
                                    text={
                                        <>
                                            <i className="material-icons">save</i>{t('Save')}
                                        </>
                                    }
                                />
                                <button
                                    className="btn btn-light d-inline-flex ml-1"
                                    onClick={() => setInEditMode(false)}
                                    disabled={pendingApiCall}>
                                    <i className="material-icons">close</i>{t('Cancel')}
                                </button>
                            </div>
                        </div>
                    )}
                    {editable && (
                        <>
                            <hr/>
                            <button className="btn btn-success d-inline-flex" onClick={() => setInEditMode(true) }>
                                <i className="material-icons">edit</i>
                                {t('Edit')}
                            </button>
                            <div className="pt-2">
                                {!props.deleteRequest && (
                                    <button className="btn btn-danger d-inline-flex" onClick={() => setModalVisible(true)}>
                                        <i className="material-icons">directions_run</i>
                                        {t('Delete My Account')}
                                    </button>
                                )}
                                {props.deleteRequest && (
                                    <button className="btn btn-primary d-inline-flex" onClick={onClickCancelDeleteUser}>
                                        <i className="material-icons">directions_run</i>
                                        {t('Cancel Account Deletion')}
                                    </button>
                                )}
                            </div>

                        </>
                    )}

                </div>
                <div className="card-footer text-muted">
                    <h6 title="Last Update"><i className="material-icons">update</i>{updated_at}</h6>
                </div>
            </div>
            {!props.deleteRequest && (
                <Modal
                    visible={modalVisible}
                    title={t('Delete My Account')}
                    okButton={t('Delete My Account')}
                    onClickCancel={onClickCancel}
                    onClickOk={onClickDeleteUserRequest}
                    message={t('Are you sure to delete your account?')}
                    pendingApiCall={pendingApiCallDeleteUser}
                />
            )}
        </div>
    );
};


export default ProfileCard;
