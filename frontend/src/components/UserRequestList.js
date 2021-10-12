import React, {useEffect, useState} from 'react';
import { getDeleteRequests } from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import UserRequestItem from './UserRequestItem';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';

const UserRequestList = (props) => {

    const [page, setPage] = useState({
        content:[],
        size: 3,
        number: 0
    });

    const [loadFailure, setLoadFailure] = useState(false);

    const pendingApiCall = useApiProgress('get','/api/userwiper/0/0');

    useEffect(() => {
        loadUsers();
    }, []);

    const { isLoggedIn} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn
    }));

    const onClickNext = () => {
        const nextPage = page.number + 1;
        loadUsers(nextPage);
    };
    const onClickPrevious = () => {
        const previousPage = page.number - 1;
        loadUsers(previousPage);
    };

    const loadUsers = async page => {
        setLoadFailure(false);
        try{
            const response = await getDeleteRequests(page,0);
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
                <h3 className="card-header text-center">{t(`Users's Delete Requests`)}</h3>
                <div className="list-group-flush">
                    {users.map(user => (
                            <UserRequestItem key={user[1].username} user={user} />
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