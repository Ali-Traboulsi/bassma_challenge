import React, {useState, useEffect} from 'react';
import styles from './RegistrationForm.module.css';
import {adminRegister} from "../../api/api";
import {pattern} from "../../constants/constants";
import {API_BASE_URL} from "../../constants/apiConstants";
import axios from "axios";
import {useHistory} from "react-router";
import Header from "../Header/Header";


const RegistrationForm = props => {


    const history = useHistory();

    // declare states
    const [state, setState] = useState({
        name: "",
        email: "",
        password: "",
        errors: {
            name:"",
            email:"",
            password: "",
        }
    });


    const redirectToHome = () => {
        history.push('/admin/home');
    }

    const handlechange = (e) => {
        const {id, value} = e.target;
        let errors = state.errors;

        switch (id) {
            case 'name':
                errors.name =
                    value.length === 0
                        ? '* Full name is required'
                        : '';
                break;
            case 'email':
                errors.email = pattern.test(value)
                    ? ''
                    : "* Email is not valid";
                break;
            case 'password':
                errors.password = (value.length < 6)
                    ? "* Password must be at least 6 characters"
                    : '';
                break;
            default:
                break;
        }

        setState(prevState => ({
            ...prevState,
            [id]: value
        }))
    }

    const handleSubmitClick = async (e) => {
        e.preventDefault();
        let errors = state.errors;
        if (state.name.length && state.email.length && state.password.length) {
            adminRegister(state, setState)
                .then(res => {
                    console.log(res);
                    if (res.success) {
                        setState(prevState => ({
                            ...prevState,
                            'successMessage' : 'Registration successful. Redirecting to home page..'
                        }));
                        redirectToHome();
                        // history.push('/admin/home');
                        // props.showError(null);
                    }
                })
                .catch(err => {
                    console.log(err);
                })
        } else {
            props.showError("Please Enter Valid name and password")
        }

    }

    return (
        <div className="card col-12 col-lg-4 login-card mt-5 hv-center mx-auto d-block">
            <Header />
            <form action="">
                <div className="form-group text-left mt-3">
                    <label htmlFor="Input Name">Your Full Name</label>
                    <input
                        type="text"
                        className="form-control"
                        id="name"
                        placeholder="Enter your full name"
                        value={state.name}
                        onChange={handlechange}
                    />
                    <div className={styles.errorMsg}>{state.errors.name}</div>
                </div>
                <div className="form-group text-left email mt-3">
                        <label htmlFor="Input Email">Email address</label>
                        <input
                            type="email"
                            className="form-control"
                            id="email"
                            aria-describedby="emailHelp"
                            placeholder="Enter Your Email"
                            name={state.email}
                            onChange={handlechange}
                        />
                        <small id="emailHelp" className="form-text text-muted ">Your Email is at Safe </small>
                    <div className={styles.errorMsg}>{state.errors.email}</div>
                </div>
                <div className="form-group text-left mt-3">
                    <label htmlFor="Input Passsword">Password</label>
                    <input
                        type="text"
                        className="form-control"
                        id="password"
                        placeholder="password"
                        name={state.password}
                        onChange={handlechange}
                    />
                    <div className={styles.errorMsg}>{state.errors.password}</div>
                </div>

                <button
                    type="submit"
                    className="btn btn-primary mb-3"
                    onClick={handleSubmitClick}
                >
                    Register
                </button>
            </form>
        </div>
    );
};

RegistrationForm.propTypes = {

};

export default RegistrationForm;