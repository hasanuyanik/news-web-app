import React, { useEffect, useState } from 'react';
import Input from '../components/Input';
import { useTranslation } from 'react-i18next';
import ButtonWithProgress from '../components/ButtonWithProgress';
import { useApiProgress } from '../shared/ApiProgress';
import { useDispatch } from 'react-redux';
import { loginHandler} from '../redux/authActions';

const UserLoginPage = (props) => {
    const [form, setForm] = useState({
        username: null,
        password: null
    });
    const [errors, setErrors] = useState({});
    const dispatch = useDispatch();

    useEffect(() => {
        setErrors({});
    }, [form.username, form.password]);

    const onChange = event => {
        const {name, value} = event.target;
        setErrors((previousErrors) => ({ ...previousErrors, [name]: undefined}));
        setForm((previousForm) => ({ ...previousForm, [name]: value}));
        // this.setState({
        //     [name]:value,
        //     errors
        // })
    }
    const onClickLogin = async event => {
        event.preventDefault();
        const creds = {
            username: form.username,
            password: form.password
        }
        const { history } = props;
        const { push } = history;

        setErrors({});
        try{
            await dispatch(loginHandler(creds));
            push('/');
        }catch(error){
            if(error.response.data.validationErrors)
            {
                setErrors(error.response.data.validationErrors);
            }
        }
        
    }
    const {t} = useTranslation();
    const {username: usernameError, password: passwordError, message: messageError} = errors;
    const pendingApiCall = useApiProgress('post','/api/1.0/auth');
        const buttonEnabled = form.username && form.password;
        return(
            <div className="container">
            <form>
                <h1 className="text-center">{t('Login')}</h1>
                <Input name="username" label={t('Username')} onChange={onChange} error={usernameError} />
                <Input name="password" label={t('Password')} onChange={onChange} error={passwordError} type="password" />
                {messageError && <div className="alert alert-danger">{messageError}</div>}
                <div className="form-group text-center">
                    <ButtonWithProgress onClick={onClickLogin} disabled={!buttonEnabled || pendingApiCall} pendingApiCall={pendingApiCall} text={t('Login')} />
                </div>
            </form>
            </div>
        );
}



export default UserLoginPage;