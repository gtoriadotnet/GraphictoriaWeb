// © XlXi 2021
// Graphictoria 5

import React from "react";
import { Link, useHistory } from "react-router-dom";

import SetTitle from "../Helpers/Title.js";

import { Card, CardTitle } from '../Layouts/Card.js';

function GenericErrorModal(props)
{
	const history = useHistory();

	return (
	<Card>
		<CardTitle>{ props.title }</CardTitle>
		<p className="card-text">{ props.children }</p>
		{
			props.stack !== undefined && process.env.NODE_ENV === 'development'
			?
				<div className="border border-primary bg-dark p-3 m-4">
					{/* this code is a jumbled mess */}
					<code>
						STACK TRACE<br/>{("-").repeat(15)}<br/>{ props.stack }
					</code>
				</div>
			:
				null
		}
		<div className="mt-2">
			<Link className="btn btn-primary px-4 me-2" to="/home">Home</Link>
			{/* eslint-disable-next-line */}
			<a className="btn btn-secondary px-4" onClick={ () => history.goBack() }>Back</a>
		</div>
	</Card>
	);
}

class NotFound extends React.Component {
	componentDidMount()
	{
		SetTitle("Not Found");
	}
	
	render()
	{
		const titles = ["OH NOES!!!", "BZZT", "ERROR", "UH OH."];
		
		return (
			<GenericErrorModal title={titles[Math.floor(Math.random() * titles.length)]}>
				We've looked far and wide and weren't able to find the page you were looking for. If you believe this is an error, contact us at <a href="mailto:support@gtoria.net" className="fw-bold text-decoration-none">support@gtoria.net</a>!
			</GenericErrorModal>
		);
	}
}

class InternalServerError extends React.Component {
	componentDidMount()
	{
		SetTitle("Internal Server Error");
	}
	
	render()
	{
		return (
			<GenericErrorModal title={<><i className="fas fa-exclamation-triangle"></i> INTERNAL SERVER ERROR</>} stack={this.props.stackTrace}>
				Oops, we ran into an issue while trying to process your request, please try again later in a few minutes. If the issue persists after a few minutes, please contact us at <a href="mailto:support@gtoria.net" className="fw-bold text-decoration-none">support@gtoria.net</a>.
			</GenericErrorModal>
		);
	}
}

export { NotFound, InternalServerError, GenericErrorModal };