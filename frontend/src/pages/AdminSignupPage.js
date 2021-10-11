import React, { useState } from 'react';
import Input from '../components/Input';
import { useTranslation } from 'react-i18next';
import ButtonWithProgress from '../components/ButtonWithProgress';
import { useApiProgress } from '../shared/ApiProgress';
import { useDispatch } from 'react-redux';
import { signupAdminHandler } from '../redux/authActions';

const AdminSignupPage = (props) => {
    const [form, setForm] = useState({
        username: null,
        password: null,
        passwordRepeat: null
    });
    const [errors, setErrors] = useState({});
    const dispatch = useDispatch();
 const onChange = event => {
        const {name, value} = event.target;
        setErrors((previousErrors) => ({ ...previousErrors, [name]: undefined}));
        setForm((previousForm) => ({ ...previousForm, [name]: value}));
    }
    const onClickSignup = async event => {
        event.preventDefault();  
        const { history } = props;
        const { push } = history;
        const {username, password} = form;
        const body = {
            username,
            password
        };
        try{
            await dispatch(signupAdminHandler(body));
            push('/');
        }catch(error){
            if(error.response.data.validationErrors){
              setErrors(error.response.data.validationErrors);
            }  
            
        }
    }
        const { t } = useTranslation();
        const {username: usernameError, password: passwordError} = errors;
        const pendingApiCallSignup = useApiProgress('post','/api/1.0/admin');
        const pendingApiCallLogin = useApiProgress('post','/api/1.0/auth');
        const pendingApiCall = pendingApiCallSignup || pendingApiCallLogin;

        let passwordRepeatError;
        if(form.password != form.passwordRepeat){
            passwordRepeatError = t('Password mismatch');
        }
        return(
            <div className="container">
            <form>
                <h1 className="text-center">{t('Sign Up')}</h1>
                <Input name="username" label={t('Username')} error={usernameError} onChange={onChange} />
                <Input name="password" label={t('Password')} error={passwordError} onChange={onChange} type="password" />
                <Input name="passwordRepeat" label={t('Password Repeat')} error={passwordRepeatError} onChange={onChange} type="password"/>
                <div className="form-group text-center">
                    <ButtonWithProgress onClick={onClickSignup} disabled={pendingApiCall || passwordRepeatError != undefined} pendingApiCall={pendingApiCall} text={t('Sign Up')}/>
                </div>
                
            </form>
            </div>

        );
}

export default AdminSignupPage;