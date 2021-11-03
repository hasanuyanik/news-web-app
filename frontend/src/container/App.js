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
import {sessionControl} from "../api/apiCalls";
import {logoutHandler} from "../redux/authActions";
import CategoryCreateForm from "../components/CategoryCreateForm";
import CategoryEditForm from "../components/CategoryEditForm";
import CategoryUserList from "../components/CategoryUserList";
import CategoryFollowUserList from "../components/CategoryFollowUserList";
import CategoryBoxList from "../components/CategoryBoxList";
import NewsCreateForm from "../components/NewsCreateForm";
import NewsEditForm from "../components/NewsEditForm";

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
          {(isLoggedIn && (role === "Admin" || role === "Moderator")) && (
                <Route path="/category/list/:pageNumber" component={CategoryList}/>
          )}
          <Route path="/categories/:pageNumber" component={CategoryBoxList}/>
          {(isLoggedIn && (role === "Admin" || role === "Moderator")) && (
            <Route path="/category/assign/list/:categoryUrl/:pageNumber" component={CategoryUserList}/>
          )}
          {(isLoggedIn && (role === "Admin" || role === "Moderator")) && (
              <Route path="/category/follow/list/:categoryUrl/:pageNumber" component={CategoryFollowUserList}/>
          )}
          {(isLoggedIn && role === "Admin") && (
              <Route path="/category/edit/:categoryUrl" component={CategoryEditForm}/>
          )}
          {(isLoggedIn && role === "Admin") && (
              <Route path="/category/create/" component={CategoryCreateForm}/>
          )}
          {(isLoggedIn && role !== "User") && (
              <Route path="/news/edit/:newsUrl" component={NewsEditForm}/>
          )}
          {(isLoggedIn && role !== "User") && (
              <Route path="/news/create/" component={NewsCreateForm}/>
          )}
          {(isLoggedIn && (role === "Admin" || role === "Moderator")) && (
              <Route path="/category/assign" component={UserList}/>
          )}
          {(isLoggedIn && (role === "Admin" || role === "Moderator")) && (
          <Route path="/requests/:pageNumber" component={UserRequestPage}/>
          )}
          {!isLoggedIn && (
          <Route path="/login" component={UserLoginPage}/>
          )}
          {!isLoggedIn && (
          <Route path="/signup" component={UserSignupPage}/>
          )}
          {isLoggedIn && (
          <Route path="/user/:username" component={UserPage}/>
          )}
          <Redirect to="/"/>
        </Switch>
      </Router>
    </div>
  );
}


export default App;
