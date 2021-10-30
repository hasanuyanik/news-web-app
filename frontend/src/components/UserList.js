import React, {useEffect, useState} from 'react';
import { getUsers } from '../api/apiCalls';
import { useTranslation } from 'react-i18next';
import UserListItem from './UserListItem';
import { useSelector } from 'react-redux';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from './Spinner';

const UserList = (props) => {

    const [page, setPage] = useState({
        content:[],
        pageNumber: 1,
        first: 1,
        last: 1
    });

    const [loadFailure, setLoadFailure] = useState(false);

    const pendingApiCall = useApiProgress('get','/api/user/1');

    useEffect(() => {
        loadUsers();
    }, []);

    const { isLoggedIn, admin, username, token} = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        username: store.username,
        token: store.token
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
        const body = {
            authUser: username,
            token
        };
        setLoadFailure(false);
        try{
            const response = await getUsers(page, body);
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
                    {first === false && <button
                        className="btn btn-sm btn-light"
                        onClick={onClickPrevious}>
                            {t('Previous')}
                    </button>}
                    {last === false && <button
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
                <h3 className="card-header text-center">{t('Users')}</h3>
                <div className="list-group-flush">
            {users.map(user => (
                    <UserListItem key={user.username} user={user} />
                )
            )}
                </div>
                {actionDiv}
                {loadFailure && <div className="text-center text-danger">{t('Load Failure')}</div>}
            </div>
           </div>
        );
    
}

export default UserList;