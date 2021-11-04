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
            <Link to={`/news/create/${url}`} className="btn d-flex" title={name} >
                {name}
            </Link>
            <i className="material-icons">category</i>
        </li>
    );
};

export default CategorySelectListItem;