import React from 'react';
import { Link } from 'react-router-dom';
import ProfileImageWithDefault from './ProfileImageWithDefault';

const UserListItem = (props) => {
    const { user } = props;
    const { username, fullname } = user;

    return (
        <Link to={`/user/${username}`} className="list-group-item list-group-item-action" >
            <ProfileImageWithDefault
                 className="img-circle rounded-circle" 
                 width="30" 
                 height="30" 
                 alt={`${username} profile`} 
                 />
            <span className="pl-2">
                {fullname}@{username}
            </span>
            
        </Link>
    );
};

export default UserListItem;