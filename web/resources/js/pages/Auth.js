// © XlXi 2021
// Graphictoria 5

import React from "react";
import { Link } from "react-router-dom";

import ReCAPTCHA from "react-google-recaptcha";

import SetTitle from "../Helpers/Title.js";

class Auth extends React.Component {
	componentDidMount()
	{
		let Locations = {
			'/login': 'Login',
			'/register': 'Register',
			'/passwordreset': 'Reset Password'
		};
		SetTitle(Locations[this.props.location]);
	}
	
	render()
	{
		let pageLabel;
		let pageContent;
		
		switch(this.props.location)
		{
			case '/login':
				pageLabel = (<><i className="fas fa-user-circle"></i> SIGN IN</>);
				pageContent = (
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
				break;
			case '/register':
				pageLabel = (<><i className="fas fa-user-plus"></i> REGISTER</>);
				pageContent = (
					<>
						<div className="px-5 mb-4">
							<div className="alert alert-warning graphictoria-alert" style={{ "borderRadius": "10px" }}>
								<p className="mb-0">Make sure your password is unique!</p>
							</div>
						</div>
						<div className="px-sm-5">
							<input type="username" className="form-control" placeholder="Username" id="uname"/>
							<p id="unameLabel" className="text-muted form-text mb-1"><br/></p>
							<input type="email" className="form-control" placeholder="Email" id="email"/>
							<p id="emailLabel" className="text-muted form-text mb-1">Make sure your email is valid, you'll need to confirm it.</p>
							<input type="password" className="form-control" placeholder="Password" id="passwd"/>
							<p id="passwdLabel" className="text-muted form-text mb-1"><br/></p>
							<input type="password" className="form-control" placeholder="Confirm password" id="passwdconf"/>
							<p id="passwdconfLabel" className="text-muted form-text pb-0 mb-0"><br/></p>
							<div className="d-flex mb-3">
								<ReCAPTCHA
									sitekey="6LeyHsUbAAAAAJ9smf-als-hXqrg7a-lHZ950-fL"
									className="mx-auto"
								/>
							</div>
							<button className="btn btn-success px-5">SIGN UP</button><br/>
							<Link to="/login" className="text-decoration-none fw-normal center">Already have an account?</Link>
							<p className="text-muted my-2">By creating an account, you agree to our <a href="/legal/terms-of-service" className="text-decoration-none fw-normal" target="_blank">Terms of Service</a> and our <a href="/legal/privacy-policy" className="text-decoration-none fw-normal" target="_blank">Privacy Policy</a>.</p>
						</div>
					</>
				);
				break;
			case '/passwordreset':
				pageLabel = (<><i className="fas fa-question-circle"></i> RESET PASSWORD</>);
				pageContent = (
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
				break;
			default:
				break;
		}
		
		return (
			<div className="container graphictoria-center-vh">
				<div className="card graphictoria-small-card shadow-sm">
					<div className="card-body text-center">
						<h5 className="card-title fw-bold">{ pageLabel }</h5>
						<hr className="mx-5"/>
						<div className="p-2 row">
							{ pageContent }
						</div>
					</div>
				</div>
			</div>
		);
	}
}

export { Auth };