// © XlXi 2021
// Graphictoria 5

import React, {useState} from 'react';
import { Link } from 'react-router-dom';

import ReCAPTCHA from 'react-google-recaptcha';
import { CreateAccount, LoginToAccount, CreateForum } from '../Helpers/Auth';
import Loader from '../Components/Loader';
import { getCookie } from '../helpers/utils';

import axios from "axios";
import Config from '../config.js';

axios.defaults.withCredentials = true

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const CreatePost = (props) => {

	const [waitingForSubmission, setWaitingForSubmission] = useState(false);
	const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
    const user = props.user;
	
	async function SubmitForm(form)
	{
        form.append('creator_id', user.id);
		setWaitingForSubmission(true);
		await axios.post(`${protocol}apis.${url}/api/create/forum`, form, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            const res = data.data;
            console.log(res);
            if (res.badInputs.length >= 1) {
                setValidity({error: true, message:res.message, inputs: res.badInputs});
				setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
                return;
            }
            window.location.replace(`/forum`);
        }).catch(error=>{console.log(error);});
		setWaitingForSubmission(false);
	}

	return (
		waitingForSubmission ? <Loader/> :
		<div className={`flex column jcc alc w-100`}>
			<div className={`flex card card-body column alc w-40`}>
                <div className={`flex row`}>
                    <div className="col-md-8 mb-2">
                        {validity.error?
                            <div className={`px-5 mb-10`}>
                                <div className={`error-dialog`}>
                                    <p className={`mb-0`}>{validity.message}</p>
                                </div>
                            </div> 
                        : null}
                        <form onSubmit={(e)=>{e.preventDefault();SubmitForm(new FormData(e.target));}} class="fs">
                        <input type="username" className={`form-control mb-4 ${(validity.inputs.find(input=>input == `title`)? `is-invalid` : ``)}`} placeholder="Title" name="title"/>
                        <textarea type="username" className={`form-control mb-4 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : ``)}`} placeholder="Body" name="body"></textarea>
                        <div className="d-flex mb-3">
                            <ReCAPTCHA
                                sitekey="6LeyHsUbAAAAAJ9smf-als-hXqrg7a-lHZ950-fL"
                                className="mx-auto"
                            />
                        </div>
                        <button className="btn btn-primary px-5" type={`submit`}>POST!</button><br/>
                        </form>
                    </div>
                    <div className="col">
                        <h5><bold>Read the rules before posting!</bold></h5>
                        <p>Before you make a post, be sure to read the <Link to={`/forum/rules`}>rules</Link>.</p>
                    </div>
                </div>
            </div>
		</div>
	);
};

export default CreatePost;