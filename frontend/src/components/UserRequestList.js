import React, {useEffect, useState} from 'react';
import { getDeleteRequests } from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import UserRequestItem from './UserRequestItem';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';
import {useParams} from "react-router";
import {Link} from "react-router-dom";

const UserRequestList = (props) => {
    const { pageNumber } = useParams();
    const { isLoggedIn, role, token, username } = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        role: store.role,
        token: store.token,
        username: store.username
    }));
    const [page, setPage] = useState({
        content:[],
        size: 3,
        number: pageNumber
    });

    const [loadFailure, setLoadFailure] = useState(false);

    const pendingApiCall = useApiProgress('get',`/api/userwiper/${page.number}/0`);

    useEffect(() => {
        loadUsers(page.number);
    }, []);

    const onClickNext = () => {
        const nextPage = page.number + 1;
        loadUsers(nextPage);
    };
    const onClickPrevious = () => {
        const previousPage = page.number - 1;
        loadUsers(previousPage);
    };

    const loadUsers = async page => {
        const creds = {
            "username": username,
            "token": token
        };
        setLoadFailure(false);
        try{
            const response = await getDeleteRequests(page,0, creds);
            console.log(response);
            setPage(response.data);
        }catch(error){
            setLoadFailure(true);
        };
    };

    const { t } = useTranslation();
    const { content : users, last, first} = page;

    let actionDiv = (
        <div>
            <Link to={`/category/list/${first}`} className="btn btn-outline-secondary m-2" title={t('Previous')} >
                {t(`Previous`)}
            </Link>
            <Link to={`/category/list/${last}`} className="btn btn-outline-secondary m-2" title={t('Next')} >
                {t(`Next`)}
            </Link>
        </div>
    );
    if(pendingApiCall){
        actionDiv = (
            <Spinner />
        );
    }

    return (
        <div className="container">
            <div className="card">
                <h3 className="card-header text-center">{t(`Users's Delete Requests`)}</h3>
                <div className="list-group-flush">
                    {users.map(user => (
                            <UserRequestItem key={user.username} user={user} />
                        )
                    )}
                </div>
                {actionDiv}
                {loadFailure && <div className="text-center text-danger">{t('Load Failure')}</div>}
            </div>
        </div>
    );

}

export default UserRequestList;