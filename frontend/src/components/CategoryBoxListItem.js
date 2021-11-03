import React, {useEffect, useState} from 'react';
import {Link, useHistory} from 'react-router-dom';
import {useTranslation} from "react-i18next";
import {useSelector} from "react-redux";
import {followCategory, followCategoryControl} from "../api/apiCalls";

const CategoryBoxListItem = (props) => {
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));
    const [ following, setFollowing] = new useState(false);
    const [CategoryCard, setCategoryCard] = useState(true);
    const history = useHistory();
    const { category } = props;
    const { t } = useTranslation();
    const { id, name, url, created_at } = category;


    useEffect(() => {
        followControl();
    },[]);

    const changeFollow = async () => {
        const body = {
            token,
            username,
            url
        };
        try{
            const response = await followCategory(body);
            setFollowing(true);
        }catch(error){
            setFollowing(false);
        };
    };

    const followControl = async () => {
        const body = {
            token,
            username,
            url
        };
        try{
           const response = await followCategoryControl(body);
           setFollowing(true);
        }catch(error){
            setFollowing(false);
        };
    };

    if (CategoryCard === false)
    {
        return (<></>);
    }

    return (
        <div className="col-md-4 col-sm-6 col-xs-12 p-3">
            <div className={"card"}>
                <div className="card-body d-inline">
                    <h5 className="card-title">{name}</h5>
                    <p className="card-text"></p>
                </div>
                <div className="card-footer">
                    <small className="text-muted">
                        <p className="btn-group" role="group" aria-label="Basic example">
                            {isLoggedIn && (
                            <button onClick={changeFollow} className={(following) ? `btn btn-danger` : `btn btn-outline-info`} title={t(`${name}'s News`)} >
                                <i className="material-icons d-block">follow_the_signs</i>
                                {t((following) ? `UnFollow` : `Follow`)}
                            </button>
                            )}
                            <Link to={`/category/${url}/news`} className="btn btn-outline-primary" title={t(`${name}'s News`)} >
                                <i className="material-icons d-block">view_list</i>
                                {t(`News`)}
                            </Link>

                        </p>
                    </small>
                </div>
            </div>
        </div>
);
};

export default CategoryBoxListItem;