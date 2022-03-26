// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useEffect, useState } from "react";
import { Link, useHistory, useParams } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';
import { Card, CardTitle } from '../Components/Card.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const User = (props) => {

    const [state, setState] = useState(true);
    const [user, setUser] = useState();
    const metaUser = props.user;
    const userId = useParams().id;
    const history = useHistory();

    const fetchUser = async () => {
        const body = new FormData();
		body.append('userId', userId);
		body.append('decision', `fetchedUser`);
        axios.post(`${protocol}apis.${url}/fetch/user`, body).then(async(data)=>{
            const res = data.data;
            if (!res) {history.push(`/`);}
            SetTitle(`${res.data.username}`);
			await setUser(res.data);
            return;
		});
    }

    useEffect(async()=>{
        await fetchUser();
        setState(false);
    }, []);
	
		return (
			!user
			?
			<Loader />
			:
			<div className={`row`}>
                <div className={`col`}>
                    <Card className={`justify-content-center`} padding={true}>
                        <CardTitle>{user.username}</CardTitle>
                        <p>[Avatar.]</p>
                    </Card>
                </div>
                <div className={`col justify-content-center`}>
                    <p>"{user.about? user.about : `${user.username} doesn't have an about section!`}" - {user.username}</p>
                </div>
            </div>
		);
}

export default User;
