import React, {useEffect, useState} from 'react';
import { getUserCategoryNewsList } from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';
import {useParams} from "react-router";
import {Link} from "react-router-dom";
import MyNewsListItem from "./MyNewsListItem";

const MyNewsList = (props) => {
    const { pageNumber, categoryUrl } = useParams();
    const [page, setPage] = useState({
        content:[],
        pageNumber,
        first: 1,
        last: 1
    });

    const [loadFailure, setLoadFailure] = useState(false);

    const pendingApiCall = useApiProgress('get',`/api/category/${pageNumber}`);

    useEffect(() => {
        loadCategories(pageNumber);
    }, []);

    const { isLoggedIn, username, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        token: store.token
    }));

    const onClickNext = () => {
        const { history } = props;
        const { push } = history;

        const nextPage = page.pageNumber + 1;
        push(`/category/${categoryUrl}/mynews/${nextPage}`);
        loadCategories(nextPage);
    };
    const onClickPrevious = () => {
        const { history } = props;
        const { push } = history;

        const previousPage = page.pageNumber - 1;
        push(`/category/${categoryUrl}/mynews/${previousPage}`);
        loadCategories(previousPage);
    };

    const loadCategories = async page => {
        const body = {
            username,
            categoryUrl,
            token
        };
        setLoadFailure(false);
        try{
            const response = await getUserCategoryNewsList(body, page);
            setPage(response.data);
        }catch(error){
            setLoadFailure(true);
        };
    };

    const { t } = useTranslation();
    const { content : newss, first, last} = page;

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
                <h3 className="card-header text-center">{t('News')}</h3>
                <div className="list-group">
                    {newss.map(news => (
                            <MyNewsListItem key={news.url} news={news} pageNumber={pageNumber} />
                        )
                    )}
                </div>
                {actionDiv}
                {loadFailure && <div className="text-center text-danger">{t('Load Failure')}</div>}
            </div>
        </div>
    );

}

export default MyNewsList;