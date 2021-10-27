import React, {useEffect, useState} from 'react';
import { Link } from 'react-router-dom';
import ProfileImageWithDefault from './ProfileImageWithDefault';
import {getRelation, getUsers} from "../api/apiCalls";
import {useSelector} from "react-redux";

const CategoryUserListItem = (props) => {
    const { user, categoryUrl } = props;
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

    const { assignStatus } = userAssign;
    const { username, fullname } = user;

    return (
        <Link to={`/user/${username}`} className="list-group-item list-group-item-action" >
            <ProfileImageWithDefault
                className="img-circle rounded-circle"
                width="30"
                height="30"
                alt={`${username} profile`}
            />
            <span className="pl-2">
                {fullname}@{username}
            </span>
            <span className="pl-2">
                {assignStatus}
            </span>
        </Link>
    );
};

export default CategoryUserListItem;