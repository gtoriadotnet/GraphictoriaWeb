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
							<h5 className="mb-0">Graphictoria aims to revive the classic Roblox experience. Join <b>5k+</b> other users and relive your childhood!</h5>
							<p className="graphictoria-homepage-fine-print fst-italic">* Graphictoria is not affiliated with or sponsored by Roblox Corporation.</p>
						</div>
						<Link to="/register" className="btn btn-success">Create your account<i className="ps-2 graphictoria-small-aligned-text fas fa-chevron-right"></i></Link>
					</div>
				</div>
				<div className="container text-center my-5">
					<h1 className="fw-bold">So what is Graphictoria?</h1>
					<h4>Ever wanted to experience or revisit classic Roblox? Graphictoria provides the platform for everyone to relive the classic Roblox experience.</h4>
				</div>
				<div className="container text-center">
					<h1 className="mb-5 fw-bold">Social Links</h1>
					<div className="row mb-5">
						<div className="col-lg-4 mb-4">
							<svg className="rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140"><image width="100%" height="100%" href="/images/social/youtube.png"></image></svg>
							<h2 className="mt-3">YouTube</h2>
							<p>Subscribe to our YouTube channel, where we upload trailers for future events and Graphictoria gameplay videos.</p>
							<a className="btn btn-primary" href="https://www.youtube.com/rbxXlXi" rel="noreferrer" target="_blank" role="button">View »</a>
						</div>
						<div className="col-lg-4 mb-4">
							<svg className="rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140"><image width="100%" height="100%" href="/images/social/twitter.png"></image></svg>
							<h2 className="mt-3">Twitter</h2>
							<p>Follow us on Twitter. Here you can recieve important updates about Graphictoria and receive announcements for events, downtime, etc.</p>
							<a className="btn btn-primary" href="https://www.twitter.com/gtoriadotnet" rel="noreferrer" target="_blank" role="button">View »</a>
						</div>
						<div className="col-lg-4 mb-4">
							<svg className="rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140"><image width="100%" height="100%" href="/images/social/discord.png"></image></svg>
							<h2 className="mt-3">Discord</h2>
							<p>Join our Discord server. This is the place where you can engage with the rest of our community, or just hang out with friends.</p>
							<a className="btn btn-primary" href="https://www.discord.gg/jBRHAyp" rel="noreferrer" target="_blank" role="button">View »</a>
						</div>
					</div>
				</div>
			</>
		);
	}
}

export { Home };