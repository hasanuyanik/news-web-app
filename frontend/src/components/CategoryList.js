import React, {useEffect, useState} from 'react';
import { getCategories } from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import CategoryListItem from './CategoryListItem';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';

const CategoryList = (props) => {

    const [page, setPage] = useState({
        content:[],
        size: 3,
        number: 0
    });

    const [loadFailure, setLoadFailure] = useState(false);

    const pendingApiCall = useApiProgress('get','/api/category/0');

    useEffect(() => {
        loadCategories();
    }, []);

    const { isLoggedIn, admin} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn
    }));

    const onClickNext = () => {
        const nextPage = page.number + 1;
        loadCategories(nextPage);
    };
    const onClickPrevious = () => {
        const previousPage = page.number - 1;
        loadCategories(previousPage);
    };

    const loadCategories = async page => {
        setLoadFailure(false);
        try{
            const response = await getCategories(page);
            console.log(response);
            setPage(response.data);
        }catch(error){
            setLoadFailure(true);
        };
    };

    const { t } = useTranslation();
    const { content : categories, last, first} = page;

    let actionDiv = (
        <div>
            {first == false && <button
                className="btn btn-sm btn-light"
                onClick={onClickPrevious}>
                {t('Previous')}
            </button>}
            {last == false && <button
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
                <h3 className="card-header text-center">{t('Categories')}</h3>
                <div className="list-group">
                    {categories.map(category => (
                            <CategoryListItem key={category.name} category={category} />
                        )
                    )}
                </div>
                {actionDiv}
                {loadFailure && <div className="text-center text-danger">{t('Load Failure')}</div>}
            </div>
        </div>
    );

}

export default CategoryList;