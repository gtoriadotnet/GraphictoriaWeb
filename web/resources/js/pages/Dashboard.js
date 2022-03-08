// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useEffect, useState } from "react";
import { Link, useHistory } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';
import { Card, CardTitle } from '../Layouts/Card.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Dashboard = (props) => {

    const [state, setState] = useState({offline: false, loading: true});
    const user = props.user;

    useEffect(()=>{
        SetTitle(`Dashboard`);
        setState({...state, loading: false});
    }, []);
	
		return (
			state.loading
			?
			<Loader />
			:
			<div className={`row`}>
                <div className={`col`}>
                    <Card className={`justify-content-center`} padding={true}>
                        <CardTitle>Hello, {user.username}!</CardTitle>
                        <p>[Avatar goes here]</p>
                    </Card>
                </div>
                <div className={`col justify-content-center`}>
                    <p>Lmao</p>
                </div>
            </div>
		);
}

export default Dashboard;
