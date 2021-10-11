import React, { useState } from 'react';
import {signup} from '../api/apiCalls';
import Input from '../components/Input';
import { useTranslation } from 'react-i18next';
import ButtonWithProgress from '../components/ButtonWithProgress';
import { useApiProgress } from '../shared/ApiProgress';
import { useDispatch } from 'react-redux';
import { signupHandler } from '../redux/authActions';

const UserSignupPage = (props) => {
    const [form, setForm] = useState({
        username: null,
        fullname: null,
        email: null,
        phone: null,
        password: null,
        passwordRepeat: null
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
    const onClickSignup = async event => {
        event.preventDefault();  
        const { history } = props;
        const { push } = history;
        const {username, fullname, email, phone, password} = form;
        const body = {
            username,
            fullname,
            email,
            phone,
            password
        };
        console.log(body);
        try{
            await dispatch(signupHandler(body));
            push('/');
        }catch(error){
            if(error.response.data.validationErrors){
              setErrors(error.response.data.validationErrors);
            }  
            
        }
    }
        const { t } = useTranslation();
        const {username: usernameError, fullname: fullnameError, email: emailError, phone: phoneError, password: passwordError} = errors;
        const pendingApiCallSignup = useApiProgress('post','/api/user/add');
        const pendingApiCallLogin = useApiProgress('post','/api/auth');
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
                <Input name="fullname" label={t('Fullname')} error={fullnameError} onChange={onChange} />
                <Input name="email" label={t('Email')} error={emailError} onChange={onChange} />
                <Input name="phone" label={t('Phone')} error={phoneError} onChange={onChange} />
                <Input name="password" label={t('Password')} error={passwordError} onChange={onChange} type="password" />
                <Input name="passwordRepeat" label={t('Password Repeat')} error={passwordRepeatError} onChange={onChange} type="password"/>
                <div className="form-group text-center">
                    <ButtonWithProgress onClick={onClickSignup} disabled={pendingApiCall || passwordRepeatError != undefined} pendingApiCall={pendingApiCall} text={t('Sign Up')}/>
                </div>
                
            </form>
            </div>

        );
}

export default UserSignupPage;