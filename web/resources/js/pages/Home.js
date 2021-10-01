// © XlXi 2021
// Graphictoria 5

import React from "react";
import { Link } from "react-router-dom";

import SetTitle from "../Helpers/Title.js";

class Home extends React.Component {
	componentDidMount()
	{
		SetTitle();
	}
	
	render()
	{
		return (
			<>
				<div className="graphictoria-home">
					<div className="container graphictoria-center-vh my-auto text-center text-white">
						<div className="mb-4 graphictoria-home-shadow">
							<h1 className="graphictoria-homepage-header">Graphictoria</h1>
							<h5 className="mb-0">Graphictoria aims to revive the classic Roblox experience. Join <b>3k+</b> other users and relive your childhood!</h5>
							<p className="graphictoria-homepage-fine-print fst-italic">* Graphictoria is not affiliated with Roblox Corporation.</p>
						</div>
						<Link to="/register" className="btn btn-success">Create your account<i className="ps-2 graphictoria-small-aligned-text fas fa-chevron-right"></i></Link>
					</div>
				</div>
				<div className="container text-center my-5">
					<h1 className="fw-bold">So what is Graphictoria?</h1>
					<h4>Ever wanted to experience or revisit classic Roblox? Graphictoria is the place for you. We currently have a 2016 client and we're planning on releasing a 2013 client soon.</h4>
				</div>
				<div className="container text-center my-5">
					<h4>Check out our socials for future updates and news about Graphictoria</h4>
				</div>
			</>
		);
	}
}

export { Home };