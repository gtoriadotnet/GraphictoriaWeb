// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useEffect, useState } from "react";
import { Link, useHistory, useParams } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';
import { BigCard, Card, CardTitle } from '../Components/Card.js';
import { getCookie, paginate } from '../helpers/utils.js';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Settings = (props) => {

    var id = useParams().id;
    const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
    const [state, setState] = useState({offline: false, loading: true});
    const user = props.user;
    const history = useHistory();

    const changeSettings = async (setting, form) => {
        switch(setting){
            case "aboutMe":
                setState({...state, loading: true});
                var body = form;
                body.append('token', encodeURIComponent(getCookie(`gtok`)));
                axios.post(`${protocol}apis.${url}/api/change/user/about`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
                    const res = data.data;
                    if (res.badInputs.length >= 1) {
                        setValidity({error: true, message:res.message, inputs: res.badInputs});
                        setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
                    }else{
                        window.location.replace(`/user/${user.id}`); //updates entire user
                    }
                    setState({...state, loading: false});
                }).catch(error=>{console.log(error);});
                break;
            case "changePassword":
                setState({...state, loading: true});
                var body = form;
                body.append('token', encodeURIComponent(getCookie(`gtok`)));
                axios.post(`${protocol}apis.${url}/api/change/user/password`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
                    const res = data.data;
                    if (res.badInputs.length >= 1) {
                        setValidity({error: true, message:res.message, inputs: res.badInputs});
                        setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
                    }else{
                        window.location.replace(`/home`); //updates entire user
                    }
                    setState({...state, loading: false});
                }).catch(error=>{console.log(error);});
                break;
            case "changeEmail":
                setState({...state, loading: true});
                var body = form;
                body.append('token', encodeURIComponent(getCookie(`gtok`)));
                axios.post(`${protocol}apis.${url}/api/change/user/email`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
                    const res = data.data;
                    if (res.badInputs.length >= 1) {
                        setValidity({error: true, message:res.message, inputs: res.badInputs});
                        setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
                    }else{
                        window.location.replace(`/home`); //updates entire user
                    }
                    setState({...state, loading: false});
                }).catch(error=>{console.log(error);});
                break;
            default:
                break;
        }
    }

    useEffect(()=>{
        /* add stuff later if necessary */
        setState({...state, loading: false});
    }, []);

		return (
			state.loading
			?
			<Loader />
			:
			<div className={`flex jcc alc w-100 column`}>
                <div class="jumbo w-60">
                    <div class="container">
                        <h1>Account Settings</h1>
                        <p>Need to change something? Welcome to the settings page!</p>
                    </div>
                </div>
                <div className="graphictoria-nav-splitter"></div>
                {validity.error?
                    <div className={`px-5 mb-10 w-60 justify-content-center align-items-center`}>
                        <div className={`error-dialog w-100`}>
                            <p className={`mb-0`}>{validity.message}</p>
                        </div>
                    </div> 
                : null}
                <div className={`column alc w-60`}>
                    <div className={`col flex jcc alc`}>
                        <Card>
                            <CardTitle>About me</CardTitle>
                            <div>
                                <form method={`OPTIONS`} onSubmit={(e)=>{e.preventDefault();changeSettings(`aboutMe`, new FormData(e.target));}} className={`flex column jcc alc`}>
                                    <textarea placeholder={`Ex. Install my virus.`} name={`body`} className={`w-100 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : null)}`}></textarea>
                                    <button className={`btn btn-success mt-15`}>Submit</button>
                                </form>
                            </div>
                        </Card>
                    </div>
                    <div className="graphictoria-nav-splitter"></div>
                    <div className={`col flex row`}>
                        <div className={`col flex jcc alc`}>
                            <BigCard className={`w-100`}>
                                <CardTitle>Change your Password</CardTitle>
                                <div>
                                    <form method={`OPTIONS`} onSubmit={(e)=>{e.preventDefault();changeSettings(`changePassword`, new FormData(e.target));}} className={`flex column jcc alc`}>
                                        <div className={`flex flex-column w-100 mb-15`}>
                                            <p>Current Password</p>
                                            <input placeholder={`Your Current Password`} className={`w-100 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : null)}`} name={`currentPassword`}/>
                                        </div>
                                        <div className={`flex flex-row w-100`}>
                                            <div className={`flex flex-column w-100 mr-15`}>
                                                <p>New Password</p>
                                                <input placeholder={`New Password`} className={`w-100 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : null)}`} name={`newPassword`}/>
                                            </div>
                                            <div className={`flex flex-column w-100`}>
                                                <p>Re-enter New Password</p>
                                                <input placeholder={`Re-enter new Password`} className={`w-100 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : null)}`} name={`checkNewPassword`}/>
                                            </div>
                                        </div>
                                        <button className={`btn btn-success mt-15`}>Submit</button>
                                    </form>
                                </div>
                            </BigCard>
                        </div>
                        <div className={`col flex row jcc alc`}>
                            <div className={`col flex jcc alc`}>
                                <BigCard className={`w-100`}>
                                    <CardTitle>Change your Email</CardTitle>
                                    <div>
                                        <form method={`OPTIONS`} onSubmit={(e)=>{e.preventDefault();changeSettings(`changeEmail`, new FormData(e.target));}} className={`flex column jcc alc`}>
                                            <div className={`flex flex-row w-100`}>
                                                <div className={`flex flex-column w-100 mr-15`}>
                                                    <p>New Email</p>
                                                    <input placeholder={`New Email`} className={`w-100 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : null)}`} name={`newEmail`}/>
                                                </div>
                                                <div className={`flex flex-column w-100`}>
                                                    <p>Current Password</p>
                                                    <input placeholder={`Current Password`} className={`w-100 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : null)}`} name={`currentPassword`}/>
                                                </div>
                                            </div>
                                            <button className={`btn btn-success mt-15`}>Submit</button>
                                        </form>
                                    </div>
                                </BigCard>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		);
}

export default Settings;
