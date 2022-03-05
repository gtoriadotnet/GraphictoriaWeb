// © XlXi 2021
// Graphictoria 5

import React, {useState} from 'react';
import { Link } from 'react-router-dom';

import ReCAPTCHA from 'react-google-recaptcha';
import { CreateAccount, LoginToAccount } from '../../Helpers/Auth';
import Loader from '../../Components/Loader';

const LoginForm = (props) => {

	const [waitingForSubmission, setWaitingForSubmission] = useState(false);
	
	const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
	
	async function SubmitLogin(form)
	{
		setWaitingForSubmission(true);
		await LoginToAccount(form).then(res=>{
			if (res != `good`) {
				setValidity({error: true, message:res.message, inputs: res.inputs});
				setTimeout(()=>{setValidity({...validity, error: false, inputs: res.inputs});}, 4000);
			}
		}).catch(error=>console.log(error));
		setWaitingForSubmission(false);
	}

	return (
		waitingForSubmission ? <Loader/> :
		<>
			<div className="col-md-8 mb-2">
				{validity.error?
					<div className={`px-5 mb-10`}>
						<div className={`error-dialog`}>
							<p className={`mb-0`}>{validity.message}</p>
						</div>
					</div> 
				: null}
				<form onSubmit={(e)=>{
					e.preventDefault();
					SubmitLogin(new FormData(e.target));
				}} class="fs">
				<input type="username" className={`form-control mb-4 ${(validity.inputs.find(input=>input == `username`)? `is-invalid` : ``)}`} placeholder="Username" name="Username"/>
				<input type="password" className={`form-control mb-4 ${(validity.inputs.find(input=>input == `password`)? `is-invalid` : ``)}`} placeholder="Password" name="Password"/>
				<div className="d-flex mb-3">
					<ReCAPTCHA
						sitekey="6LeyHsUbAAAAAJ9smf-als-hXqrg7a-lHZ950-fL"
						className="mx-auto"
					/>
				</div>
				<button className="btn btn-primary px-5" type={`submit`}>SIGN IN</button><br/>
				</form>
				<Link to="/passwordreset" className="text-decoration-none fw-normal center">Forgot your password?</Link>
			</div>
			<div className="col">
				<h5>New to Graphictoria?</h5>
				<p>Creating an account takes less than a minute, and you can join a community of 7k+ users for <b>completely free</b>.<br/><Link to="/register" className="text-decoration-none fw-normal">Sign Up</Link></p>
			</div>
		</>
	);
};

export default LoginForm;