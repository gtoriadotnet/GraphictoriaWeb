// © XlXi 2021
// Graphictoria 5

import React, { useState } from 'react';
import { Link } from 'react-router-dom';

import ReCAPTCHA from 'react-google-recaptcha';

import Loader from '../../Components/Loader.js';
import { CreateAccount } from '../../Helpers/Auth.js';

const RegisterForm = (props) => {
	const RegistrationAreas = [
		{
			text: 'Username',
			type: 'username',
			id: 'username'
		},
		{
			text: 'Email',
			type: 'email',
			id: 'email'
		},
		{
			text: 'Password',
			type: 'password',
			id: 'password'
		},
		{
			text: 'Confirm password',
			type: 'password',
			id: 'confirmation'
		}
	];
	
	const [waitingForSubmission, setWaitingForSubmission] = useState(false);
	
	const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
	
	async function SubmitRegistration(form)
	{
		const formData = new FormData(form);
		setWaitingForSubmission(true);
		await CreateAccount(formData).then(res=>{
			if (res != `good`) {
				setValidity({error: true, message:res.message, inputs: res.inputs});
				setTimeout(()=>{setValidity({...validity, error: false, inputs: res.inputs});}, 4000);
			}
			window.location.replace(`/home`);
		}).catch(error=>console.log(error));
		setWaitingForSubmission(false);
	}
	
	return (
		waitingForSubmission
		?
		<Loader />
		:
		(
			<>
				<div className="px-5 mb-2">
					<div className="alert alert-warning graphictoria-alert" style={{ "borderRadius": "10px" }}>
						<p className="mb-0">Make sure your password is unique!</p>
					</div>
				</div>
				{validity.error?
					<div className={`px-5 mb-10`}>
						<div className={`error-dialog`}>
							<p className={`mb-0`}>{validity.message}</p>
						</div>
					</div> 
				: null}
				<div className="px-sm-5">
					<form onSubmit={(e)=>{e.preventDefault();SubmitRegistration(e.target);}}>
					{
						RegistrationAreas.map(({ text, type, id }, index) => 
							<input key={ index } onChange={(e)=>{}} type={ type } className={ `form-control mb-2 ${(validity.inputs.find(input=>input == id)? `is-invalid` : ``)}` } placeholder={ text } id={ id } name={id}/>
						)
					}
					<div className="mt-3">
						<div className="d-flex mb-2">
							<ReCAPTCHA
								sitekey="6LeyHsUbAAAAAJ9smf-als-hXqrg7a-lHZ950-fL"
								className="mx-auto"
							/>
						</div>
						<button className="btn btn-success px-5">SIGN UP</button>
					</div>
					</form>
					<Link to="/login" className="text-decoration-none fw-normal center">Already have an account?</Link>
					<p className="text-muted my-2">By creating an account, you agree to our <a href="/legal/terms-of-service" className="text-decoration-none fw-normal" target="_blank">Terms of Service</a> and our <a href="/legal/privacy-policy" className="text-decoration-none fw-normal" target="_blank">Privacy Policy</a>.</p>
				</div>
			</>
		)
	);
};

export default RegisterForm;

/*
const [values, setValues] = useState({
		username: '',
		email: '',
		password: '',
		confirmation: ''
	});
const [validity, setValidity] = useState({
		username: false,
		email: false,
		password: false,
		confirmation: false
	});
	
	const [validityMessages, setValidityMessages] = useState({
		username: 'test',
		email: '',
		password: '',
		confirmation: ''
	});
	
	const handleChange = (e) => {
		const {id, value} = e.target;
		
		setValues(prevState => ({
			...prevState,
			[id] : value
		}));
    }
*/
