import React, { useEffect, useState, useRef } from 'react';
import logo from '../assets/hoaxify.png';
import {Link} from 'react-router-dom';
import {useTranslation} from 'react-i18next';
import { useDispatch, useSelector } from 'react-redux';
import {logoutHandler} from '../redux/authActions';
import ProfileImageWithDefault from './ProfileImageWithDefault';

const TopBar = (props) => {
const {t} = useTranslation();
const { username, isLoggedIn, fullname, image, role, token} = useSelector((store) => ({
    isLoggedIn: store.isLoggedIn,
    username: store.username,
    fullname: store.fullname,
    image: store.image,
    role: store.role,
    token: store.token
}));

const menuArea = useRef(null);
const [menuVisible, setMenuVisible] = useState(false);

useEffect(() => {
    document.addEventListener('click', menuClickTracker)
    return () => {
        document.removeEventListener('click', menuClickTracker);
    };
}, [isLoggedIn]);

const menuClickTracker = event => {
    if(menuArea.current == null || !menuArea.current.contains(event.target)){
        setMenuVisible(false);
    }
};

const dispatch = useDispatch();
const onLogoutSuccess = async event => {
    event.preventDefault();
    const creds = {
        token
    }
    dispatch(logoutHandler(creds));
};
   
let links = (
<ul className="navbar-nav ms-auto">
<li>
    <Link className="nav-link" to="/login">
        {t("Login")}
    </Link>
</li>
<li>
    <Link className="nav-link" to="/signup">
        {t("Sign Up")}
    </Link>
</li>
<li>
    <Link className="nav-link" to="/admin/login">
        {t("Admin Login")}
    </Link>
</li>
</ul>
);

    if(isLoggedIn){
        let dropDownClass = 'dropdown-menu p-0 shadow';
        if(menuVisible){
            dropDownClass += ' show';
        }
        links = (
            <ul className="navbar-nav ms-auto" ref={menuArea}>
            <li className="nav-item dropdown">
            <div className="nav-link d-flex" style={{ cursor: 'pointer'}} onClick={()=> setMenuVisible(true)}>
                <ProfileImageWithDefault 
                image={image} 
                width="32" height="32" 
                className="rounded-circle img-circle m-auto"/>
                <span className="dropdown-toggle p-2">{fullname}{!fullname && `${username}-${role}`}</span>
            </div>
            <div className={dropDownClass}>
                {role == "user" && (
                <Link className="dropdown-item d-flex" to={`/user/${username}`}  onClick={()=> setMenuVisible(false)}>
                    <i className="material-icons text-info">person</i>
                    <span className="">{t("My Profile")}</span>
                </Link>
                )}
                <span className="dropdown-item d-flex" onClick={onLogoutSuccess} style={{ cursor: 'pointer'}}>
                    <i className="material-icons text-info">power_settings_new</i>
                    <span className="">{t("Logout")}</span>
                </span> 
            </div>
            </li>
            </ul>
        ); 
    };

    return (
        <div className="shadow-sm bg-light mb-2">
            <nav className="navbar navbar-light navbar-expand container">
                <Link className="navbar-brand d-flex" to="/">
                    {/* <img src={logo} width="60" alt="User&Admin Logo"/> */}
                    <span className="m-auto p-2">User&Admin</span>
                </Link>
                {links}
               </nav>
        </div>
        
    );
        
       
    
}


export default TopBar;

