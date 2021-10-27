import React, {useEffect, useState} from 'react';
import Input from '../components/Input';
import { useTranslation } from 'react-i18next';
import ButtonWithProgress from '../components/ButtonWithProgress';
import { useApiProgress } from '../shared/ApiProgress';
import {useDispatch, useSelector} from 'react-redux';
import {editCategoryHandler} from '../redux/authActions';
import {getCategory, getUsers} from "../api/apiCalls";
import {useParams} from "react-router";

const CategoryAssignUserForm = (props) => {
    const { categoryUrl } = useParams();
    const [notFound, setNotFound] = useState(false);
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));
    const [form, setForm] = useState({
        id: null,
        name: null,
        url: null
    });
    useEffect(() => {
        setNotFound(false);
    }, [form]);

    useEffect(() => {
        loadCategory();
    }, []);

    const loadCategory = async () => {
        setNotFound(false);
        try{
            const response = await getUsers();
            setForm(response.data);
        }catch(error){
            setNotFound(true);
        };
    };

    const [errors, setErrors] = useState({});
    const dispatch = useDispatch();
    const onChange = event => {
        const {name, value} = event.target;
        setErrors((previousErrors) => ({ ...previousErrors, [name]: undefined}));
        setForm((previousForm) => ({ ...previousForm, [name]: value}));
        // this.setState({
        //     [name]:value,
        //     errors
        // })
    }
    const onClickEditCategory = async event => {
        event.preventDefault();
        const { history } = props;
        const { push } = history;
        const { id, name, url} = form;
        const body = {
            username,
            token,
            id,
            name,
            url
        };
        try{
            await dispatch(editCategoryHandler(body));
            push('/category/list/1');
        }catch(error){
            if(error.response.data.validationErrors){
                setErrors(error.response.data.validationErrors);
            }
        }
    }
    const { t } = useTranslation();
    const {name: nameError, url: urlError} = errors;
    const pendingApiCallEdit = useApiProgress('post',`/api/category/show/${categoryUrl}`);
    const pendingApiCall = pendingApiCallEdit;

    if (!isLoggedIn || notFound === true)
    {
        return (
            <div className="container">
                <div className="alert alert-danger text-center">
                    <div>
                        <i className="material-icons" style={{ fontSize: '48px' }}>unauthorized</i>
                    </div>
                    {t('User not found')}
                </div>
            </div>
        );
    }

    return(
        <div className="container">
            <form>
                <h1 className="text-center">{t('Category Edit')}</h1>
                <Input name="name" label={t('Category Name')} error={nameError} onChange={onChange} defaultValue={form.name}/>
                <Input name="url" label={t('Url')} error={urlError} onChange={onChange} defaultValue={form.url}/>
                <div className="form-group text-center">
                    <ButtonWithProgress onClick={onClickEditCategory} disabled={pendingApiCall} pendingApiCall={pendingApiCall} text={t('Save')}/>
                </div>

            </form>
        </div>

    );
}

export default CategoryAssignUserForm;