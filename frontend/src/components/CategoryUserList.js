import React, {useEffect, useState} from 'react';
import {getCategories, getCategoryUserList, getUsers} from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import CategoryUserListItem from './CategoryUserListItem';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';
import {useParams} from "react-router";
import {Link} from "react-router-dom";
import CategoryAssignUserForm from "./CategoryAssignUserForm";

const CategoryUserList = (props) => {
    const { pageNumber, categoryUrl } = useParams();

    const [page, setPage] = useState({
        content:[],
        pageNumber: 1,
        first: 1,
        last: 1
    });
    const { username, isLoggedIn, role, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        role: store.role,
        token: store.token
    }));

    const [loadFailure, setLoadFailure] = useState(false);

    const pendingApiCall = useApiProgress('get',`/api/user/${pageNumber}`);

    useEffect(() => {
        loadUsers(categoryUrl, pageNumber);
    }, []);

    const onClickNext = () => {
        const { history } = props;
        const { push } = history;

        const nextPage = page.pageNumber + 1;
        push(`/category/assign/list/${categoryUrl}/${nextPage}`);
        loadUsers(nextPage);
    };
    const onClickPrevious = () => {
        const { history } = props;
        const { push } = history;

        const previousPage = page.pageNumber - 1;
        push(`/category/assign/list/${categoryUrl}/${previousPage}`);
        loadUsers(previousPage);
    };

    const loadUsers = async (categoryUrl, page) => {
        const body = {
            authUser: username,
            token,
            categoryUrl
        };
        setLoadFailure(false);
        try{
            const response = await getUsers(page, body);
            console.log(response.data);
            setPage(response.data);
        }catch(error){
            setLoadFailure(true);
        };
    };

    const { t } = useTranslation();
    const { content : users, first, last} = page;

    let actionDiv = (
        <div>
            {first >= 1 && <button
                className="btn btn-sm btn-light"
                onClick={onClickPrevious}>
                {t('Previous')}
            </button>}
            {last >= 1 && <button
                className="btn btn-sm btn-light float-right"
                onClick={onClickNext}>
                {t('Next')}
            </button>}
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
                <h3 className="card-header text-center">{t(`Assignment User List`)}</h3>
                <div className="list-group">
                    {users.map(user => (
                            <CategoryUserListItem key={user.username} user={user} pageNumber={pageNumber} categoryUrl={categoryUrl} />
                        )
                    )}
                </div>
                {actionDiv}
                {loadFailure && <div className="text-center text-danger">{t('Load Failure')}</div>}
            </div>
        </div>
    );

}

export default CategoryUserList;