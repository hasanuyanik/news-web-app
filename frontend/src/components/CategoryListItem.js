import React, {useState} from 'react';
import {Link, useHistory} from 'react-router-dom';
import {useTranslation} from "react-i18next";
import {useSelector} from "react-redux";
import {deleteCategory} from "../api/apiCalls";
import {useParams} from "react-router";

const CategoryListItem = (props) => {
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));
    const history = useHistory();
    const { category } = props;
    const { t } = useTranslation();
    const { id, name, url, created_at } = category;
    const onClickDeleteCategory = async event => {
        event.preventDefault();
        const body = {
            username,
            token,
            name,
            url
        };
        try{
            await deleteCategory(body);
            history.push('/category/list/0');
        }catch(error){
            if(error.response.data.validationErrors){
                console.log(error.response.data.validationErrors);
            }

        }
    }
    return (
    <li className="list-group-item d-flex justify-content-between align-items-center">
        <div className="d-inline">
            {name}
        </div>
        <div className="badge badge-primary badge-pill">
            <blockquote className="blockquote mb-0">
                <p className="btn-group" role="group" aria-label="Basic example">
                    <Link to={`/category/${url}/appointee`} className="btn btn-outline-dark" title={t('Assignment List')} >
                        <i className="material-icons d-block">assignment_ind</i>
                        {t(`Assignment List`)}
                    </Link>
                    <Link to={`/category/edit/${url}`} className="btn btn-outline-success" title={t('Edit Category')} >
                        <i className="material-icons d-block">edit</i>
                        {t(`Edit`)}
                    </Link>
                    <button className="btn btn-outline-danger" title={t('Delete Category')} onClick={onClickDeleteCategory}>
                        <i className="material-icons d-block">cancel</i>
                        {t(`Delete`)}
                    </button>
                    <Link to={`/category/${url}/news`} className="btn btn-outline-primary" title={t(`${name}'s News`)} >
                        <i className="material-icons d-block">view_list</i>
                        {t(`News`)}
                    </Link>
                </p>
            </blockquote>
        </div>
    </li>
    );
};

export default CategoryListItem;