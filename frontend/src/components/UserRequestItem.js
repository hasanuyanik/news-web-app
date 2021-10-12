import React from 'react';
import { Link } from 'react-router-dom';
import {useTranslation} from "react-i18next";
import {cancelDeleteUser, deleteUser} from "../api/apiCalls";
import {useHistory} from "react-router";
import {useSelector} from "react-redux";

const UserRequestItem = (props) => {
    const { user } = props;
    const { username, fullname } = user[1];
    const { t } = useTranslation();
    const history = useHistory();
    const { isLoggedIn, role, token } = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        role: store.role,
        token: store.token
    }));

    const onClickCancelDeleteUser = async () => {
        const body = {
            username,
            token
        };
        await cancelDeleteUser(body);
    };

    const onClickDeleteUser = async () => {
        const body = {
            username,
            token
        };
        await deleteUser(body);
    };

    return (
        <Link to={`/user/${username}`} className="list-group-item list-group-item-action" >
                <button className="btn btn-danger d-inline-flex m-1" title={t('Delete Account')} onClick={onClickDeleteUser}>
                    <i className="material-icons">person_off</i>
                </button>

                <button className="btn btn-primary d-inline-flex m-1" title={t('Cancel Deletion')} onClick={onClickCancelDeleteUser}>
                        <i className="material-icons">cancel</i>

                </button>
            <span className="pl-2 m-3">
                {fullname}@{username}
            </span>

        </Link>
    );
};

export default UserRequestItem;