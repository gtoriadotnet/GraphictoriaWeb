// © XlXi 2021
// Graphictoria 5

import React from 'react';
import { Link } from 'react-router-dom';

import ReCAPTCHA from 'react-google-recaptcha';

import SetTitle from '../Helpers/Title.js';
import { CreateAccount } from '../Helpers/Auth.js';

import { Card, CardTitle } from '../Components/Card.js';

import LoginForm from './Auth/Login.js';
import ForgotPasswordForm from './Auth/ForgotPassword.js';
import RegisterForm from './Auth/Register.js';

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
				pageContent = (<LoginForm />);
				break;
			case '/register':
				pageLabel = (<><i className="fas fa-user-plus"></i> REGISTER</>);
				pageContent = (
					<RegisterForm />
				);
				break;
			case '/passwordreset':
				pageLabel = (<><i className="fas fa-question-circle"></i> RESET PASSWORD</>);
				pageContent = (<ForgotPasswordForm />);
				break;
			default:
				pageLabel = (<><i className={`"fas fa-question-circle"`}></i> YOU'RE LOGGED IN!</>);
				pageContent = (<div><div>Sorry, this page is for unauthenticated members only!</div></div>);
				break;
		}
		
		return (
			<Card>
				<CardTitle>{ pageLabel }</CardTitle>
				<div className="p-2 row">
					{ pageContent }
				</div>
			</Card>
		);
	}
}

export { Auth };