// © XlXi 2021
// Graphictoria 5

import React, {useEffect, useState} from 'react';
import { Link, useHistory, useParams } from 'react-router-dom';

import ReCAPTCHA from 'react-google-recaptcha';
import { CreateAccount, LoginToAccount, CreateForum } from '../Helpers/Auth';
import Loader from '../Components/Loader';
import { getCookie } from '../helpers/utils';
import { Card, CardTitle } from '../Components/Card';

import axios from "axios";
import Config from '../config.js';

axios.defaults.withCredentials = true

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const CreateReply = (props) => {

	const [waitingForSubmission, setWaitingForSubmission] = useState(false);
	const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
    const [post, setPost] = useState({loading: true, post: []});
    const user = props.user;
    const postId = useParams().id;
    const history = useHistory();

    useEffect(async()=>{
        await axios.get(`${protocol}apis.${url}/fetch/post/${postId}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {history.push(`/forum`);}
            const res = data.data;
            setPost({loading: false, post: res.post});
        }).catch(error=>{console.log(error);});
    }, []);
	
	async function SubmitForm(form)
	{
        form.append('creator_id', user.id);
        form.append('token', encodeURIComponent(getCookie(`gtok`)));
		setWaitingForSubmission(true);
		await axios.post(`${protocol}apis.${url}/api/create/reply/${post.post.id}`, form, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            const res = data.data;
            console.log(res);
            if (res.badInputs.length >= 1) {
                setValidity({error: true, message:res.message, inputs: res.badInputs});
				setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
            }else{
                history.push(`/forum/post/${res.post_id}`);
            }
        }).catch(error=>{console.log(error);});
		setWaitingForSubmission(false);
	}

	return (
		waitingForSubmission && !post.loading? <Loader/> :
		<Card>
			<CardTitle>Reply to '{post.post.title}'</CardTitle>
                <div className="p-2 row">
                    <div className="col-md-8 mb-2">
                        {validity.error?
                            <div className={`px-5 mb-10`}>
                                <div className={`error-dialog`}>
                                    <p className={`mb-0`}>{validity.message}</p>
                                </div>
                            </div> 
                        : null}
                        <form onSubmit={(e)=>{e.preventDefault();SubmitForm(new FormData(e.target));}} class="fs">
                        <textarea type="username" className={`form-control mb-4 ${(validity.inputs.find(input=>input == `body`)? `is-invalid` : ``)}`} placeholder="Body" name="body"></textarea>
                        <div className="d-flex mb-3">
                            <ReCAPTCHA
                                sitekey="6LeyHsUbAAAAAJ9smf-als-hXqrg7a-lHZ950-fL"
                                className="mx-auto"
                            />
                        </div>
                        <button className="btn btn-primary px-5" type={`submit`}>REPLY!</button><br/>
                        </form>
                    </div>
                    <div className="col">
                        <h5><bold>Read the rules before replying!</bold></h5>
                        <p>Before you make a post, be sure to read the <Link to={`/forum/rules`}>rules</Link>.</p>
                    </div>
                </div>
            </Card>
	);
};

export default CreateReply;