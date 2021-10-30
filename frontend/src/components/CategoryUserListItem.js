import React, {useEffect, useState} from 'react';
import { Link } from 'react-router-dom';
import ProfileImageWithDefault from './ProfileImageWithDefault';
import {getRelation, getUsers} from "../api/apiCalls";
import {useSelector} from "react-redux";
import {useTranslation} from "react-i18next";
import Input from "./Input";

const CategoryUserListItem = (props) => {
    const { user, categoryUrl } = props;
    const { t } = useTranslation();
    const [userAssign, setUserAssign] = useState({
        username: null,
        fullname: null,
        assignStatus: null
    });
    const [assign, setAssign] = useState(false);

    const { username:authUser, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));

    useEffect(() => {
        relationControl();
    }, []);

    const relationControl = async (categoryUrl, username) => {
        const body = {
            authUser,
            token,
            username,
            categoryUrl
        };
        setAssign(false);
        try{
            const response = await getRelation(body);
            setUserAssign(response.data);
        }catch(error){
            setAssign(true);
        };
    };

    const assignControl = async () => {
      alert(username);
    };

    const { assignStatus } = userAssign;
    const { username, fullname } = user;

    const [isChecked, setIsChecked] = useState(true);

    return (
    <li className="list-group-item d-flex justify-content-between align-items-center">
        <ProfileImageWithDefault
            className="img-circle rounded-circle"
            width="30"
            height="30"
            alt={`${username} profile`}
        />
        <div className="p-2">
            {fullname}@{username}
        </div>
        <div className="badge badge-primary badge-pill ms-auto">
            <blockquote className="blockquote mb-0">
                <p className="btn-group" role="group" aria-label="Basic example">
                    <div className="form-check form-switch text-dark">
                        <Input
                            className={"form-check-input"}
                            label={t('Checked')} type="checkbox"
                            id="flexSwitchCheckChecked"
                            onChange={assignControl}
                            onClick={() => setIsChecked(!isChecked)}
                            checked={isChecked} />
                    </div>
                </p>
            </blockquote>
        </div>
    </li>
    );
};

export default CategoryUserListItem;