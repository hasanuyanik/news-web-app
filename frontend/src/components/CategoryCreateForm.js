import React, { useState } from 'react';
import Input from '../components/Input';
import { useTranslation } from 'react-i18next';
import ButtonWithProgress from '../components/ButtonWithProgress';
import { useApiProgress } from '../shared/ApiProgress';
import {useDispatch, useSelector} from 'react-redux';
import {createCategoryHandler} from '../redux/authActions';

const CategoryCreateForm = (props) => {
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));
    const [form, setForm] = useState({
        name: null,
        url: null
    });
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
    const onClickCreateCategory = async event => {
        event.preventDefault();
        const { history } = props;
        const { push } = history;
        const {name, url} = form;
        const body = {
            username,
            token,
            name,
            url
        };
        try{
            await dispatch(createCategoryHandler(body));
            push('/category/list');
        }catch(error){
            if(error.response.data.validationErrors){
                setErrors(error.response.data.validationErrors);
            }

        }
    }
    const { t } = useTranslation();
    const {name: nameError, url: urlError} = errors;
    const pendingApiCallCreate = useApiProgress('post','/api/category/add');
    const pendingApiCall = pendingApiCallCreate;

    if(!isLoggedIn){
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
                <h1 className="text-center">{t('Category Create')}</h1>
                <Input name="name" label={t('Category Name')} error={nameError} onChange={onChange} />
                <Input name="url" label={t('Url')} error={urlError} onChange={onChange} />
                <div className="form-group text-center">
                    <ButtonWithProgress onClick={onClickCreateCategory} disabled={pendingApiCall} pendingApiCall={pendingApiCall} text={t('Create')}/>
                </div>

            </form>
        </div>

    );
}

export default CategoryCreateForm;