import React from "react";
import ApiProgress from "../shared/ApiProgress";
import UserSignupPage from "../pages/UserSignupPage";
import UserLoginPage from "../pages/UserLoginPage";
import AdminSignupPage from "../pages/AdminSignupPage";
import AdminLoginPage from "../pages/AdminLoginPage";
import LanguageSelector from "../components/LanguageSelector";
import HomePage from "../pages/HomePage";
import {HashRouter as Router, Redirect, Route, Switch} from 'react-router-dom';
import UserPage from '../pages/UserPage';
import TopBar from "../components/TopBar";
import { useSelector } from 'react-redux';
import UserList from "../components/UserList";

const App = () => { 
  const { isLoggedIn, role } = useSelector((store) => ({
    isLoggedIn: store.isLoggedIn,
    role: store.role
  }));
  return (
    <div>
      <Router>
        <TopBar />
        <Switch>

        <Route exact path="/" component={UserList}/>

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
    <LanguageSelector />
    </div>
  );
}


export default App;
