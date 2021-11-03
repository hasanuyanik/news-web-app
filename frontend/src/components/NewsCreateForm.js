import React, { useState } from 'react';
import Input from '../components/Input';
import { useTranslation } from 'react-i18next';
import ButtonWithProgress from '../components/ButtonWithProgress';
import { useApiProgress } from '../shared/ApiProgress';
import {useDispatch, useSelector} from 'react-redux';
import {createNewsHandler} from '../redux/authActions';

const NewsCreateForm = (props) => {
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));
    const [newImage, setNewImage] = useState();
    const [form, setForm] = useState({
        title: null,
        url: null,
        description: null,
        content: null
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

    const onClickCreateNews = async event => {
        event.preventDefault();
        const { history } = props;
        const { push } = history;
        let image;
        if(newImage){
            image = newImage.split(',')[1];
        }

        const {title, url, description, content} = form;
        const body = {
            username,
            token,
            title,
            url,
            description,
            content,
            image: newImage
        };
        try{
            await dispatch(createNewsHandler(body));
            push('/category/list/1');
        }catch(error){
            if(error.response.data.validationErrors){
                setErrors(error.response.data.validationErrors);
            }

        }
    }

    const onChangeFile = (event) => {
        if(event.target.files.length < 1){
            return;
        }
        const file = event.target.files[0];

        console.log(event.target.files);

        console.log(event.target.files[0]);
        console.log(event.target.files[0].name);
        console.log(event.target.files[0].size);
        console.log(event.target.files[0].type);

        const fileReader = new FileReader();
        fileReader.onloadend = () => {
            setNewImage(fileReader.result);

            console.log(fileReader.result);
        };
        fileReader.readAsDataURL(file);

        console.log(file);
    }

    const { t } = useTranslation();
    const {title: titleError, url: urlError, description: descriptionError, content: contentError, img: imgError} = errors;
    const pendingApiCallCreate = useApiProgress('post','/api/news/add');
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
                <h1 className="text-center">{t('News Create')}</h1>
                <Input name="title" label={t(`News's Title`)} error={titleError} onChange={onChange} />
                <Input name="url" label={t('Url')} error={urlError} onChange={onChange} />
                <Input name="description" label={t('Description')} error={descriptionError} onChange={onChange} />

                <div className="form-group m-2">
                    <label>{t(`Content`)}</label>
                    <textarea className={"form-control"} name="content" onChange={onChange}></textarea>
                    <div className="invalid-feedback">{contentError}</div>
                </div>

                <Input type={"file"} label={t(`News's Image`)} error={imgError} onChange={onChangeFile} />
                <div className="form-group text-center">
                    <ButtonWithProgress onClick={onClickCreateNews} disabled={pendingApiCall} pendingApiCall={pendingApiCall} text={t('Create')}/>
                </div>

            </form>
        </div>

    );
}

export default NewsCreateForm;