import React, {useEffect, useState} from 'react';
import { getCategories } from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import CategoryBoxListItem from './CategoryBoxListItem';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';
import {useParams} from "react-router";
import {Link} from "react-router-dom";

const CategoryBoxList = (props) => {
    const { pageNumber } = useParams();
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

    const { isLoggedIn, admin} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn
    }));

    const onClickNext = () => {
        const { history } = props;
        const { push } = history;

        const nextPage = page.pageNumber + 1;
        push(`/categories/${nextPage}`);
        loadCategories(nextPage);
    };
    const onClickPrevious = () => {
        const { history } = props;
        const { push } = history;

        const previousPage = page.pageNumber - 1;
        push(`/categories/${previousPage}`);
        loadCategories(previousPage);
    };

    const loadCategories = async page => {
        setLoadFailure(false);
        try{
            const response = await getCategories(page);
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
        <div className="card-deck container">
            <div className={"row"}>
                {categories.map(category => (
                        <CategoryBoxListItem key={category.name} category={category} pageNumber={pageNumber}/>
                    )
                )}
                {actionDiv}
                {loadFailure && <div className="text-center text-danger">{t('Load Failure')}</div>}
            </div>
        </div>
    );

}

export default CategoryBoxList;