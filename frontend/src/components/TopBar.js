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
const menuArea1 = useRef(null);
const menuArea2 = useRef(null);
const userMenuArea = useRef(null);

const [userMenuVisible, setUserMenuVisible] = useState(false);
const [accountMenuVisible, setAccountMenuVisible] = useState(false);
const [menuVisible, setMenuVisible] = useState(false);
const [menuVisible1, setMenuVisible1] = useState(false);
const [catMenuVisible ,setCatMenuVisible] = useState(false);

useEffect(() => {
    document.addEventListener('click', menuClickTracker)
    return () => {
        document.removeEventListener('click', menuClickTracker);
    };
}, [isLoggedIn]);

const menuClickTracker = event => {
    if(menuArea.current == null || !menuArea.current.contains(event.target)){
        setMenuVisible(false);
        setAccountMenuVisible(false);
    }
    if(menuArea1.current == null || !menuArea1.current.contains(event.target)){
        setMenuVisible1(false);
    }
    if(menuArea2.current == null || !menuArea2.current.contains(event.target)){
        setCatMenuVisible(false);
    }
    if(userMenuArea.current == null || !userMenuArea.current.contains(event.target)){
        setUserMenuVisible(false);
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

let themeColor = "bg-light navbar-light";
    themeColor = (role === "Admin") ? "bg-dark navbar-dark" : themeColor;
    themeColor = (role === "Moderator") ? "bg-danger navbar-dark" : themeColor;
    themeColor = (role === "Editor") ? "bg-warning navbar-light" : themeColor;

let accountlinks = (
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
</ul>
);

let categoryLinks = (<></>);
let requestLinks = (<></>);
let userLinks = (<></>);

let dropDownClass = '';
let accountDropDownClass = '';

if(menuVisible)
{
    dropDownClass = 'show';
}

if(accountMenuVisible)
{
   accountDropDownClass = 'show';
}


    if(isLoggedIn){
        let dropDownClass1 = 'dropdown-menu p-0 shadow';
        if(menuVisible1){
            dropDownClass1 += ' show';
        }
        accountlinks = (
            <ul className="navbar-nav ms-auto">
                {(isLoggedIn && role !== "User") && (
                    <li className={`nav-item ${themeColor}`}>
                        <Link className="nav-link d-flex" to={`/news/create`} >
                            <i className={`material-icons p-1`}>add</i>
                            <span className="p-1">{t("Create News")}</span>
                        </Link>
                    </li>
                )}
            <li className={`nav-item dropdown ${themeColor}`} ref={menuArea1}>
                <span className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                   style={{ cursor: 'pointer'}} onClick={()=> setMenuVisible1(!menuVisible1)}>
                    <ProfileImageWithDefault
                        image={image}
                        width="32" height="32"
                        className="rounded-circle img-circle m-auto"/>
                    <span className="p-2">{fullname}{!fullname && `${username}-${role}`}</span>
                </span>
                <div className={dropDownClass1} aria-labelledby="navbarDropdown">
                    <Link className="dropdown-item d-flex" to={`/user/${username}`}  onClick={()=> setMenuVisible1(false)}>
                        <i className="material-icons text-info p-1">person</i>
                        <span className="p-1">{t("My Profile")}</span>
                    </Link>
                    <div className="dropdown-divider"></div>
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
            <li className="nav-item dropdown" ref={menuArea2}>
                <span className="nav-link dropdown-toggle" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style={{ cursor: 'pointer'}} onClick={()=> setCatMenuVisible(!catMenuVisible)}>
                    {t("Category Actions")}
                </span>
                <div className={dropDownClass2} aria-labelledby="navbarDropdown">
                    {role === "Admin" && (
                        <Link className="dropdown-item d-flex" to={`/category/create`}  onClick={()=> setCatMenuVisible(false)}>
                            <i className="material-icons p-1">add_circle</i>
                            <span className="p-1">{t("Create")}</span>
                        </Link>
                    )}
                    <Link className="dropdown-item d-flex" to={`/category/list/1`}  onClick={()=> setCatMenuVisible(false)}>
                        <i className="material-icons p-1">format_list_bulleted</i>
                        <span className="p-1">{t("Category List")}</span>
                    </Link>
                </div>
            </li>
        );

        let dropDownClass3 = 'dropdown-menu p-0 shadow';
        if(userMenuVisible){
            dropDownClass3 += ' show';
        }
        userLinks = (
            <li className="nav-item dropdown" ref={userMenuArea}>
                <span className="nav-link dropdown-toggle" id="navbarDropdown" role="button"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style={{ cursor: 'pointer'}} onClick={()=> setUserMenuVisible(!userMenuVisible)}>
                    {t("User Actions")}
                </span>
                <div className={dropDownClass3} aria-labelledby="navbarDropdown">
                    <Link className="dropdown-item d-flex" to={`/auth/user/1`}  onClick={()=> setUserMenuVisible(false)}>
                        <i className="material-icons p-1">add_circle</i>
                        <span className="p-1">{t("Users Auth")}</span>
                    </Link>
                    <Link className="dropdown-item d-flex" to={`/activity/1`}  onClick={()=> setUserMenuVisible(false)}>
                        <i className="material-icons p-1">assignment_ind</i>
                        <span className="p-1">{t("User Activity")}</span>
                    </Link>
                    <Link className="dropdown-item d-flex" to={`/requests/1`}  onClick={()=> setUserMenuVisible(false)}>
                        <i className="material-icons p-1">add_circle</i>
                        <span className="p-1">{t("User Delete Requests")}</span>
                    </Link>
                </div>
            </li>
        );
    };

    return (
        <div className={`shadow-sm mb-2 p-2 pt-0 pb-0 ${themeColor}`}>
            <nav className={`navbar navbar-expand-lg ${themeColor}`} ref={menuArea}>
                <button className="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation"
                        onClick={()=> setMenuVisible(!menuVisible)}
                >
                    <i className="material-icons p-1">{(menuVisible) ? "close" : "menu"}</i>
                </button>
                <a className="navbar-brand" href="#">Turkish News</a>
                <button className="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarAccountContent" aria-controls="navbarAccountContent"
                        aria-expanded="false" aria-label="Toggle navigation"
                        onClick={()=> setAccountMenuVisible(!accountMenuVisible)}
                >
                    <i className="material-icons p-1">{(accountMenuVisible) ? "close" : "menu"}</i>
                </button>

                <div className={`collapse navbar-collapse ${dropDownClass}`} id="navbarSupportedContent">
                    <ul className="navbar-nav mr-auto">
                        <li className="nav-item active">
                            <a className="nav-link" href="#">Home <span className="sr-only">(current)</span></a>
                        </li>
                        <li className="nav-item active">
                            <Link className="nav-link" to={`/categories/1`}  onClick={()=> setUserMenuVisible(false)}>
                                <span className="">{t("News Categories")}</span>
                            </Link>
                        </li>
                        <li className="nav-item dropdown">
                            <a className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Dropdown
                            </a>
                            <div className="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a className="dropdown-item" href="#">Action</a>
                                <a className="dropdown-item" href="#">Another action</a>
                                <div className="dropdown-divider"></div>
                                <a className="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
                        {(role !== "User") && (categoryLinks)}

                        {(role === "Admin" || role === "Moderator") && (userLinks)}
                        <li className="nav-item">
                            <a className="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                </div>
                <div className={`collapse navbar-collapse ${accountDropDownClass}`} id="navbarAccountContent">
                    {accountlinks}
                </div>
            </nav>

        </div>

    );
        
       
    
}


export default TopBar;

