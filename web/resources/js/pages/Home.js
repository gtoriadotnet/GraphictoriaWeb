// © XlXi 2021
// Graphictoria 5

import React, { useEffect } from "react";
import { Link } from "react-router-dom";

import SetTitle from "../Helpers/Title.js";

import SocialCard from "../Components/Landing/SocialCard.js";
import { user } from "../helpers/utils.js";

const Home = () => {
	useEffect(()=>{
		SetTitle();
	}, [])
	
		return (
			<>
				<div className="graphictoria-home">
					<div className="container graphictoria-center-vh my-auto text-center text-white">
						<div className="mb-4 graphictoria-home-shadow">
							<h1 className="graphictoria-homepage-header">Graphictoria</h1>
							<h5 className="mb-0">Graphictoria aims to revive the classic Roblox experience. Join <b>7k+</b> other users and relive your childhood!</h5>
							<p className="graphictoria-homepage-fine-print fst-italic">* Graphictoria is not affiliated with, endorsed by, or sponsored by Roblox Corporation.</p>
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
						<SocialCard title="YouTube" description="Subscribe to our YouTube channel, where we upload trailers for future events and Graphictoria gameplay videos." link="https://www.youtube.com/graphictoria?sub_confirmation=1" />
						<SocialCard title="Twitter" description="Follow us on Twitter. Here you can recieve important updates about Graphictoria and receive announcements for events, potential downtime, status reports, etc." link="https://twitter.com/intent/user?screen_name=gtoriadotnet" />
						<SocialCard title="Discord" description="Join our Discord server. This is the place where you can engage with the rest of our community, or just hang out with friends." link="https://www.discord.gg/jBRHAyp" />
					</div>
				</div>
			</>
		);
}

export { Home };