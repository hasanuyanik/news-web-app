import React, {useState} from 'react';
import {Link, useHistory} from 'react-router-dom';
import {useTranslation} from "react-i18next";
import {useSelector} from "react-redux";
import {deleteCategory} from "../api/apiCalls";
import {useParams} from "react-router";

const CategorySelectListItem = (props) => {
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));
    const [CategoryCard, setCategoryCard] = useState(true);
    const history = useHistory();
    const { category } = props;
    const { t } = useTranslation();
    const { id, name, url, created_at } = category;
    const onClickDeleteCategory = async event => {
        const { push } = history;
        event.preventDefault();
        const body = {
            username,
            token,
            name,
            url
        };
        try{
            await deleteCategory(body);
            setCategoryCard(false);
            push(`/category/list/${props.pageNumber}`);
        }catch(error){
            if(error.response.data.validationErrors){
                console.log(error.response.data.validationErrors);
            }

        }
    }

    if (CategoryCard === false)
    {
        return (<></>);
    }

    return (
        <li className="list-group-item d-flex justify-content-between align-items-center">
            <div className="d-inline">
                {name}
            </div>
            <div className="badge badge-primary badge-pill">
                <blockquote className="blockquote mb-0">
                    <p className="btn-group" role="group" aria-label="Basic example">
                        <Link to={`/news/create/${url}`} className="btn btn-outline-success" title={t('Edit Category')} >
                            <i className="material-icons d-block">add_circle</i>
                            {t(`Add News in This Category`)}
                        </Link>
                        <Link to={`/category/${url}/mynews/1`} className="btn btn-outline-primary" title={t(`${name}'s Your News`)} >
                            <i className="material-icons d-block">view_list</i>
                            {t(`Your News in This Category`)}
                        </Link>
                    </p>
                </blockquote>
            </div>
        </li>
    );
};

export default CategorySelectListItem;