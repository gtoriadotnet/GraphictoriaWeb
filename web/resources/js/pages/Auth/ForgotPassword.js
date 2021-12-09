// © XlXi 2021
// Graphictoria 5

import React from 'react';
import { Link } from 'react-router-dom';

import ReCAPTCHA from 'react-google-recaptcha';

const ForgotPasswordForm = (props) => {
	return (
		<div className="col-md-11 mx-auto">
			<input type="username" className="form-control mb-3" placeholder="Username"/>
			<div className="d-flex mb-3">
				<ReCAPTCHA
					sitekey="6LeyHsUbAAAAAJ9smf-als-hXqrg7a-lHZ950-fL"
					className="mx-auto"
				/>
			</div>
			<button className="btn btn-primary px-5">RESET PASSWORD</button><br/>
			<Link to="/login" className="text-decoration-none fw-normal center">Login?</Link>
		</div>
	);
};

export default ForgotPasswordForm;