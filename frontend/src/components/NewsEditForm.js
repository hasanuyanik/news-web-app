import React, {useEffect, useState} from 'react';
import Input from '../components/Input';
import {Link} from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import ButtonWithProgress from '../components/ButtonWithProgress';
import { useApiProgress } from '../shared/ApiProgress';
import {useDispatch, useSelector} from 'react-redux';
import {editNewsHandler} from '../redux/authActions';
import {getNews} from "../api/apiCalls";
import {useParams} from "react-router";

const NewsEditForm = (props) => {
    const { newsUrl } = useParams();
    const [notFound, setNotFound] = useState(false);
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
        content: null,
        img: null
    });
    const [category, setCategory] = useState({
        name: null,
        url: null
    });
    useEffect(() => {
        setNotFound(false);
    }, [form]);

    useEffect(() => {
        loadNews();
    }, []);

    const loadNews = async () => {
        setNotFound(false);
        try{
            const response = await getNews(newsUrl);
            setForm(response.data.content[0]);
            setCategory(response.data.content[1]);
        }catch(error){
            setNotFound(true);
        };
    };

    const [errors, setErrors] = useState({});
    const [response, setResponse] = useState({});
    const [modal, setModal] = useState(false);
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
    const onClickEditNews = async event => {
        event.preventDefault();
        const { history } = props;
        const { push } = history;
        const { id, title, url, description, content, img} = form;
        let image;
        if(newImage){
            image = newImage.split(',')[1];
        }
        const body = {
            username,
            token,
            id,
            title,
            url,
            description,
            content,
            image: newImage
        };
        try{
            const result = await dispatch(editNewsHandler(body));
            setResponse(result.data);
            setModal(true);
            push(`/news/edit/${newsUrl}`);
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

        const fileReader = new FileReader();
        fileReader.onloadend = () => {
            setNewImage(fileReader.result);
        };
        fileReader.readAsDataURL(file);
    }

    let modalClass = "modal";
    if (modal === true)
    {
        modalClass += " fade show d-block";
    }
    else
    {
        modalClass += " fade";
    }

    const { t } = useTranslation();
    const {title: titleError, url: urlError, description: descriptionError, content: contentError, img: imgError, message: messageError} = errors;
    const {title, url, description, content, img} = form;
    const {name: categoryName, url: categoryUrl} = category;
    const {message: resultMessage} = response;
    const pendingApiCallEdit = useApiProgress('post',`/api/news/show/${newsUrl}`);
    const pendingApiCall = pendingApiCallEdit;

    if (!isLoggedIn || notFound === true)
    {
        return (
            <div className="container">
                <div className="alert alert-danger text-center">
                    <div>
                        <i className="material-icons" style={{ fontSize: '48px' }}>unauthorized</i>
                    </div>
                    {t('News not found')}
                </div>
            </div>
        );
    }

    return(
        <>
        <div className="container">
        <form>
            <Link className="btn d-flex text-black-50" to={`/category/${categoryUrl}/news/1`} >
                <i className="material-icons text-secondary p-1">category</i>
                <span className="p-1">{t(`Category: ${categoryName}`)}</span>
            </Link>
            <h1 className="text-center">{t('News Edit')}</h1>
            <Input name="title" label={t(`News's Title`)} error={titleError} onChange={onChange} defaultValue={title} />
            <Input name="url" label={t('Url')} error={urlError} onChange={onChange} defaultValue={url} />
            <Input name="description" label={t('Description')} error={descriptionError} onChange={onChange} defaultValue={description} />

            <div className="form-group m-2">
                <label>{t(`Content`)}</label>
                <textarea className={`form-control ${(contentError ? "is-invalid" : "")}`} name="content" onChange={onChange} value={content} ></textarea>
                <div className="invalid-feedback">{contentError}</div>
            </div>

            <div className={"d-flex p-2"}>
                <img src={`../storage/News/${img}`} className="img-fluid rounded-start" alt={title} />
            </div>

            <Input type={"file"} label={t(`News's Image`)} error={imgError} onChange={onChangeFile} />
            {messageError && (<h5 className={"text-danger text-center"}>{messageError}</h5>)}
            <div className="form-group text-center">
                <ButtonWithProgress onClick={onClickEditNews} disabled={pendingApiCall} pendingApiCall={pendingApiCall} text={t('Save')}/>
            </div>
        </form>
    </div>
            {resultMessage && (<div className={modalClass} style={{ backgroundColor: '#000000b0' }}>
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <button type="button" className="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" onClick={() => setModal(false)}></button>
                        </div>
                        <div className="modal-body">
                            <p>{resultMessage}</p>
                        </div>
                    </div>
                </div>
            </div>)}
            </>
    );
}

export default NewsEditForm;