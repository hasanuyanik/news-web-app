import React, { useState, useEffect } from 'react';
import ProfileCard  from '../components/ProfileCard';
import {getDeleteRequest, getUser, getUserAdmin} from '../api/apiCalls';
import { useParams } from 'react-router';
import { useTranslation } from 'react-i18next';
import { useApiProgress } from '../shared/ApiProgress';
import Spinner from '../components/Spinner';
import {useSelector} from "react-redux";

const UserPage = () => {
    const [user, setUser] = useState({});
    const [deleteRequest, setDeleteRequest] = useState();
    const [notFound, setNotFound] = useState(false);
    const { username } = useParams();
    const {t} = useTranslation();
    const pendingApiCall = useApiProgress('get','/api/user/' + username);
    const { isLoggedIn, role, token } = useSelector((store) => ({
        isLoggedIn: store.isLoggedIn,
        role: store.role,
        token: store.token
    }));
    useEffect(() => {
        setNotFound(false);
    }, [user]);

    useEffect(() => {
        const loadUser = async () => {
            const body = {
                username,
                token
            };
            try{
            const response = await getUser(username);
            setUser(response.data[0]);
            const responseRequest = await getDeleteRequest(body);
            console.log(responseRequest);
            setDeleteRequest(responseRequest.data["DeleteRequest"]);
            }catch(error){
                setNotFound(true);
            }
        }
        loadUser();
    }, [username]);
    
    if(pendingApiCall){
        return (
            <Spinner />
        );
    }

    if(notFound){
        return (
            <div className="container">
                <div className="alert alert-danger text-center">
                    <div>
                        <i className="material-icons" style={{ fontSize: '48px' }}>error</i>
                    </div>
                    {t('User not found')}
                </div>    
            </div>
        );
    }
    
    return (
        <div className="container">
            <ProfileCard user={user} token={token} deleteRequest={deleteRequest}/>
        </div>
    );
};

export default UserPage;