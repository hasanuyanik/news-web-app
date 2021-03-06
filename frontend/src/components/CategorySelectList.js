import React, {useEffect, useState} from 'react';
import { getUserCategoryList } from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';
import {useParams} from "react-router";
import {Link} from "react-router-dom";
import CategorySelectListItem from "./CategorySelectListItem";

const CategorySelectList = (props) => {
    const { pageNumber } = useParams();
    const [page, setPage] = useState({
        content:[],
        pageNumber,
        first: 1,
        last: 1
    });

    const [loadFailure, setLoadFailure] = useState(false);

    const pendingApiCall = useApiProgress('get',`/api/user/category/list/${pageNumber}`);

    useEffect(() => {
        loadCategories(pageNumber);
    }, []);

    const { isLoggedIn, token, username} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        token: store.token,
        username: store.username
    }));

    const onClickNext = () => {
        const { history } = props;
        const { push } = history;

        const nextPage = page.pageNumber + 1;
        push(`/news/category/${nextPage}`);
        loadCategories(nextPage);
    };
    const onClickPrevious = () => {
        const { history } = props;
        const { push } = history;

        const previousPage = page.pageNumber - 1;
        push(`/news/category/${previousPage}`);
        loadCategories(previousPage);
    };

    const loadCategories = async page => {
        const body = {
            username,
            token
        };
        setLoadFailure(false);
        try{

            const response = await getUserCategoryList(body, page);
            setPage(response.data);
        }catch(error){
            setLoadFailure(true);
        };
    };

    const { t } = useTranslation();
    const { content : categories, first, last} = page;

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
                <h3 className="card-header text-center">{t('Your Categories Assigned to Add News')}</h3>
                <div className="list-group">
                    {categories.map(category => (
                            <CategorySelectListItem key={category.name} category={category} pageNumber={pageNumber}/>
                        )
                    )}
                </div>
                {actionDiv}
                {loadFailure && <div className="text-center text-danger">{t('Load Failure')}</div>}
            </div>
        </div>
    );

}

export default CategorySelectList;