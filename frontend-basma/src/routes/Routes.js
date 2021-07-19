import React from 'react';
import {
    BrowserRouter as Router,
    Route,
    Switch,
    Redirect,
    useHistory
} from "react-router-dom";
import PropTypes from 'prop-types';
import {ACCESS_TOKEN} from "../constants/apiConstants";
import RegistrationForm from "../components/RegistrationForm/RegistrationForm";
import AdminHome from "../pages/AdminHome";

const authRoute = (Component) => {
    if (localStorage.getItem(ACCESS_TOKEN)) {
        return <Component />
    } else {
        return <Redirect to="/admin/login" />
    }
}

const Routes = props => {
    return (
        <Router {...props}>
            <Switch>
                <Route exact path="/">
                    <Redirect to="/admin/home"/>
                </Route>
                <Route path="/admin/register" component={RegistrationForm}/>
                <Route path="/admin/home" component={AdminHome}/>
            </Switch>
        </Router>
    );
};

Routes.propTypes = {
    
};

export default Routes;