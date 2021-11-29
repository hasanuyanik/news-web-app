import React, {useState} from 'react';
import {Link, useHistory} from 'react-router-dom';
import {useTranslation} from "react-i18next";
import {useSelector} from "react-redux";
import {deleteCategory, deleteNews} from "../api/apiCalls";
import {useParams} from "react-router";

const MyNewsListItem = (props) => {
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));
    const [NewsCard, setNewsCard] = useState(true);
    const history = useHistory();
    const { news } = props;
    const { t } = useTranslation();
    const { id, title, url, description, content, img, created_at, updated_at } = news;
    const onClickDeleteNews = async event => {
        const { push } = history;
        event.preventDefault();
        const body = {
            username,
            token,
            id
        };
        try{
            await deleteNews(body);
            setNewsCard(false);
            push(`/category/${props.categoryUrl}/mynews/${props.pageNumber}`);
        }catch(error){
            if(error.response.data.validationErrors){
                console.log(error.response.data.validationErrors);
            }

        }
    }

    if (NewsCard === false)
    {
        return (<></>);
    }

    return (
        <li className="list-group-item d-flex justify-content-between align-items-center">
            <div className="card mb-3 col-md-12 shadow">
                <div className="row">
                    <div className="col-md-4">
                        <img src={`../storage/News/${img}`} className="img-fluid rounded-start" alt={title} />
                    </div>
                    <div className="col-md-4">
                        <div className="card-body">
                            <h5 className="card-title">{title}</h5>
                            <p className="card-text">{description}</p>
                            <p className="card-text"><small className="text-muted">Last updated {updated_at}</small></p>
                        </div>
                    </div>
                    <div className="col col-md-4">
                        <div className="badge badge-primary badge-pill p-4 float-end">
                            <blockquote className="blockquote mb-0 shadow-sm">
                                <p className="btn-group" role="group" aria-label="Basic example">
                                    <Link to={`/news/${url}`} className="btn btn-outline-secondary" title={t(`Go to News`)} >
                                        <i className="material-icons d-block">visibility</i>
                                        {t(`Go to News`)}
                                    </Link>
                                    <Link to={`/news/edit/${url}`} className="btn btn-outline-success" title={t('Edit News')} >
                                        <i className="material-icons d-block">edit</i>
                                        {t(`Edit`)}
                                    </Link>
                                    <button className="btn btn-outline-danger" title={t('Delete News')} onClick={onClickDeleteNews}>
                                        <i className="material-icons d-block">cancel</i>
                                        {t(`Delete`)}
                                    </button>
                                </p>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    );
};

export default MyNewsListItem;