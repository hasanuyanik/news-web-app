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

const menuArea1 = useRef(null);
const menuArea2 = useRef(null);
const [menuVisible, setMenuVisible] = useState(false);
const [catMenuVisible ,setCatMenuVisible] = useState(false);

useEffect(() => {
    document.addEventListener('click', menuClickTracker)
    return () => {
        document.removeEventListener('click', menuClickTracker);
    };
}, [isLoggedIn]);

const menuClickTracker = event => {
    if(menuArea1.current == null || !menuArea1.current.contains(event.target)){
        setMenuVisible(false);
    }
    if(menuArea2.current == null || !menuArea2.current.contains(event.target)){
        setCatMenuVisible(false);
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
   
let links1 = (
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

let categoryLinks = (<></>);

    if(isLoggedIn){
        let dropDownClass1 = 'dropdown-menu p-0 shadow';
        if(menuVisible){
            dropDownClass1 += ' show';
        }
        links1 = (
            <ul className="navbar-nav ms-auto" ref={menuArea1}>
            <li className="nav-item dropdown">
            <div className="nav-link d-flex" style={{ cursor: 'pointer'}} onClick={()=> setMenuVisible(true)}>
                <ProfileImageWithDefault 
                image={image} 
                width="32" height="32" 
                className="rounded-circle img-circle m-auto"/>
                <span className="dropdown-toggle p-2">{fullname}{!fullname && `${username}-${role}`}</span>
            </div>
            <div className={dropDownClass1}>
                {role == "user" && (
                <Link className="dropdown-item d-flex" to={`/user/${username}`}  onClick={()=> setMenuVisible(false)}>
                    <i className="material-icons text-info p-1">person</i>
                    <span className="p-1">{t("My Profile")}</span>
                </Link>
                )}
                <span className="dropdown-item d-flex" onClick={onLogoutSuccess} style={{ cursor: 'pointer'}}>
                    <i className="material-icons text-info p-1">power_settings_new</i>
                    <span className="p-1">{t("Logout")}</span>
                </span> 
            </div>
            </li>
            </ul>
        );

        let dropDownClass2 = 'dropdown-menu p-0 shadow';
        if(catMenuVisible){
            dropDownClass2 += ' show';
        }
        categoryLinks = (
            <ul className="navbar-nav" ref={menuArea2}>
                <li className="nav-item dropdown">
                    <div className="nav-link d-flex" style={{ cursor: 'pointer'}} onClick={()=> setCatMenuVisible(true)}>
                        <i className="material-icons p-2">category</i>
                        <span className="dropdown-toggle p-2">{t("Category Actions")}</span>
                    </div>
                    <div className={dropDownClass2}>
                        {role == "user" && (
                            <Link className="dropdown-item d-flex" to={`/category/create`}  onClick={()=> setCatMenuVisible(false)}>
                                <i className="material-icons p-1">add_circle</i>
                                <span className="p-1">{t("Create")}</span>
                            </Link>
                        )}
                        <Link className="dropdown-item d-flex" to={`/category/assign`}  onClick={()=> setCatMenuVisible(false)}>
                            <i className="material-icons p-1">assignment_ind</i>
                            <span className="p-1">{t("Category Assign")}</span>
                        </Link>
                        <Link className="dropdown-item d-flex" to={`/category/list/0`}  onClick={()=> setCatMenuVisible(false)}>
                            <i className="material-icons p-1">format_list_bulleted</i>
                            <span className="p-1">{t("Category List")}</span>
                        </Link>
                    </div>
                </li>
            </ul>
        );
    };

    return (
        <div className="shadow-sm bg-light mb-2">
            <nav className="navbar navbar-light navbar-expand container">
                {categoryLinks}
                <Link className="navbar-brand ms-auto" to="/">
                    {/* <img src={logo} width="60" alt="User&Admin Logo"/> */}
                    <span className="m-auto p-2">Turkish News</span>
                </Link>
                {links1}
               </nav>
        </div>
        
    );
        
       
    
}


export default TopBar;

