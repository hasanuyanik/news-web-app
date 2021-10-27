import React from "react";
import ApiProgress from "../shared/ApiProgress";
import UserSignupPage from "../pages/UserSignupPage";
import UserLoginPage from "../pages/UserLoginPage";
import AdminSignupPage from "../pages/AdminSignupPage";
import AdminLoginPage from "../pages/AdminLoginPage";
import LanguageSelector from "../components/LanguageSelector";
import HomePage from "../pages/HomePage";
import {HashRouter as Router, Redirect, Route, Switch, useHistory} from 'react-router-dom';
import UserPage from '../pages/UserPage';
import TopBar from "../components/TopBar";
import {useDispatch, useSelector} from 'react-redux';
import UserList from "../components/UserList";
import CategoryList from "../components/CategoryList";
import UserRequestPage from "../components/UserRequestList";
import {cancelDeleteUser, logout, sessionControl} from "../api/apiCalls";
import {logoutHandler} from "../redux/authActions";
import CategoryCreateForm from "../components/CategoryCreateForm";
import CategoryEditForm from "../components/CategoryEditForm";
import CategoryUserList from "../components/CategoryUserList";

const App = () => {
  const history = useHistory();
  const dispatch = useDispatch();
  const { isLoggedIn, role, token, username } = useSelector((store) => ({
    isLoggedIn: store.isLoggedIn,
    role: store.role,
    token: store.token,
    username: store.username
  }));




  const onLogoutSuccess = async () => {
    const creds = {
      token
    }
    dispatch(logoutHandler(creds));
  };

  const sessionController = async () => {
    const body = {
      username,
      token
    };
    try {
      await sessionControl(body);
    }
    catch (errors)
    {
      await onLogoutSuccess();
    }
  };

  sessionController();

  return (
    <div>
      <Router>
        <TopBar />
        <Switch>

        <Route exact path="/" component={UserList}/>

          {isLoggedIn && (
                <Route path="/category/list/:pageNumber" component={CategoryList}/>
          )}
            {isLoggedIn && (
                <Route path="/category/assign/list/:categoryUrl/:pageNumber" component={CategoryUserList}/>
            )}
          {isLoggedIn && (
              <Route path="/category/edit/:categoryUrl" component={CategoryEditForm}/>
          )}
          {isLoggedIn && (
              <Route path="/category/create/" component={CategoryCreateForm}/>
          )}
          {isLoggedIn && (
              <Route path="/category/assign" component={UserList}/>
          )}
        {isLoggedIn && (
        <Route path="/requests/:pageNumber" component={UserRequestPage}/>
        )}
        {!isLoggedIn && (
        <Route path="/login" component={UserLoginPage}/>
        )}
        {!isLoggedIn && (
        <Route path="/signup" component={UserSignupPage}/>
        )}
        {!isLoggedIn && (
        <Route path="/admin/login" component={AdminLoginPage}/>
        )}
        {!isLoggedIn && (
        <Route path="/admin/signup" component={AdminSignupPage}/>
        )}
        <Route path="/user/:username" component={UserPage}/>
        <Redirect to="/"/>
        </Switch>
      </Router>
    </div>
  );
}


export default App;
