// © XlXi 2021
// Graphictoria 5

import React from 'react';
import { Link } from 'react-router-dom';

import ReCAPTCHA from 'react-google-recaptcha';

const LoginForm = (props) => {
	return (
		<>
			<div className="col-md-8 mb-2">
				<input type="username" className="form-control mb-4" placeholder="Username"/>
				<input type="password" className="form-control mb-3" placeholder="Password"/>
				<div className="d-flex mb-3">
					<ReCAPTCHA
						sitekey="6LeyHsUbAAAAAJ9smf-als-hXqrg7a-lHZ950-fL"
						className="mx-auto"
					/>
				</div>
				<button className="btn btn-primary px-5">SIGN IN</button><br/>
				<Link to="/passwordreset" className="text-decoration-none fw-normal center">Forgot your password?</Link>
			</div>
			<div className="col">
				<h5>New to Graphictoria?</h5>
				<p>Creating an account takes less than a minute, and you can join a community of 6k+ users for <b>completely free</b>.<br/><Link to="/register" className="text-decoration-none fw-normal">Sign Up</Link></p>
			</div>
		</>
	);
};

export default LoginForm;