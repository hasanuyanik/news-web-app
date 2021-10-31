import React, {useEffect, useState} from 'react';
import { Link } from 'react-router-dom';
import ProfileImageWithDefault from './ProfileImageWithDefault';
import {assignCategory, getRelation, getUsers} from "../api/apiCalls";
import {useSelector} from "react-redux";
import {useTranslation} from "react-i18next";
import Input from "./Input";

const CategoryUserListItem = (props) => {
    const { user, categoryUrl } = props;
    const { username, fullname } = user;
    const { t } = useTranslation();
    const [isAssign, setIsAssign] = useState(false);

    const { username:authUser, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));

    useEffect(() => {
        relationControl(categoryUrl, username);
    }, []);

    const relationControl = async (categoryUrl, username) => {
        const body = {
            authUser,
            token,
            username,
            url: categoryUrl
        };

        try{
            const response = await getRelation(body);

            setIsAssign(true);
        }catch(error){
            setIsAssign(false);
        };
    };

    const assignControl = async () => {
        const body = {
            authUser,
            token,
            username,
            url: categoryUrl
        };
        try{
            const response = await assignCategory(body);
            setIsAssign(true);
        }catch(error){
            setIsAssign(false);
        };
    };

    const [isChecked, setIsChecked] = useState(true);

    return (
    <li className={`list-group-item d-flex justify-content-between position-relative align-items-center`}>
        <ProfileImageWithDefault
            className="img-circle rounded-circle"
            width="30"
            height="30"
            alt={`${username} profile`}
        />
        <div className="p-2">
            {fullname}@{username}
        </div>
        <div className="badge badge-primary badge-pill ms-auto text-success fw-normal">
            <blockquote className="blockquote mb-0">
                <p className="btn-group" role="group" aria-label="Basic example">
                    <div className="form-check form-switch">
                        <Input
                            className={"form-check-input"}
                            label={isAssign && t("Assigned")} type="checkbox"
                            id="flexSwitchCheckChecked"
                            onChange={assignControl}
                            checked={isAssign} />
                    </div>
                </p>
            </blockquote>
        </div>
    </li>
    );
};

export default CategoryUserListItem;