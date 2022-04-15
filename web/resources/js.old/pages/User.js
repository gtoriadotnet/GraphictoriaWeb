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

    const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
    const [state, setState] = useState(true);
    const [user, setUser] = useState();
    const [isFriend, setFriend] = useState(false);
    const metaUser = props.user;
    const userId = useParams().id;
    const history = useHistory();

    const fetchUser = async () => {
        const body = new FormData();
		body.append('userId', userId);
		body.append('decision', `fetchedUser`);
        axios.get(`${protocol}apis.${url}/fetch/user/${userId}`, body).then(async(data)=>{
            const res = data.data;
            if (!res) {history.push(`/`);}
            SetTitle(`${res.data.username}`);
            setFriend(res.data.isFriend);
			await setUser(res.data);
            return;
		});
    }

    const addFriend = async (decision) => {
        const body = new FormData();
		body.append('decision', decision);
        setState(true);
        axios.post(`${protocol}apis.${url}/api/add/user/${userId}`, body).then(async(data)=>{
            const res = data.data;
            if (res.badInputs.length >= 1) {
                setValidity({error: true, message:res.message, inputs: res.badInputs});
				setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
				setFeedState({...feedState, loading: false});
                setState(false);
                return;
            }
            await setFriend(res.data);
            setState(false);
            return;
		});
    }

    useEffect(async()=>{
        await fetchUser();
        setState(false);
    }, []);
	
		return (
			!user || state
			?
			<Loader />
			:
			<div className={`row`}>
                {validity.error?
                    <div className={`px-5 mb-10`}>
                        <div className={`error-dialog`}>
                            <p className={`mb-0`}>{validity.message}</p>
                        </div>
                    </div> 
                : null}
                <div className={`col`}>
                    <Card className={`justify-content-center`} padding={true}>
                        <CardTitle>{user.username}</CardTitle>
                        <img src='/images/testing/avatar.png' className='img-fluid gt-charimg' />
                        <hr/>
                        <div className={`flex flex-column justify-content-center align-items-center`}>
                            {
                            !metaUser? null : 
                            userId == metaUser.id? <Link className={`btn btn-primary w-fit-content`} to={`/auth/settings`}>Settings</Link> :
                            isFriend && isFriend == `pending`?
                                <button className={`btn btn-dark disabled w-fit-content`}>Pending...</button>
                            : isFriend && isFriend == `needToAccept`?
                                <button className={`btn btn-success w-fit-content`} onClick={(e)=>{addFriend(`accept`);}}>Accept Friend Request</button>
                            : isFriend? 
                                <button className={`btn btn-danger w-fit-content`} onClick={(e)=>{addFriend(`remove`);}}>Remove Friend</button>
                            : 
                                <button className={`btn btn-success w-fit-content`} onClick={(e)=>{addFriend(`add`);}}>Add Friend</button>
                            }
                        </div>
                        <hr/>
                        <p>"{user.about? user.about : `${user.username} doesn't have an about section!`}" - {user.username}</p>
                    </Card>
                </div>
                <div className={`col justify-content-center`}>
                    <p>Something else idk.</p>
                </div>
            </div>
		);
}

export default User;
