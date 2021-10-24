// © XlXi 2021
// Graphictoria 5

import React from 'react';
import { Link } from 'react-router-dom';

function FooterLink(props)
{
	return (
		<Link className="text-decoration-none fw-normal" to={props.href}>{props.label}</Link>
	);
}

const Footer = () => {
	let CurrentDate = new Date();
	
	return (
		<div className="footer mt-auto pt-3 text-center shadow-lg">
			<div className="container">
				<h4 className="fw-bold mb-0">Graphictoria</h4>
				<p className="text-muted fw-bold mb-0 mt-1"><FooterLink label="About Us" href="/legal/about-us"/> | <FooterLink label="Terms of Service" href="/legal/terms-of-service"/> | <FooterLink label="Privacy Policy" href="/legal/privacy-policy"/> | <FooterLink label="DMCA" href="/legal/dmca"/> | <FooterLink label="Support" href="/support"/> | <FooterLink label="Blog" href="/blog"/></p>
				<hr className="mx-auto my-2 w-25"/>
				<p className="text-muted fw-light m-0">Copyright © {CurrentDate.getFullYear()} Graphictoria. All rights reserved.</p>
				<p className="text-muted fw-light m-0">Graphictoria is not affiliated with, endorsed by, or sponsored by Roblox Corporation. The usage of this website signifies your acceptance of the <FooterLink label="Terms of Service" href="/legal/terms-of-service"/> and our <FooterLink label="Privacy Policy" href="/legal/privacy-policy"/>.</p>
				<div className="my-1">
					<a className="mx-1" href="https://twitter.com/gtoriadotnet" rel="noreferrer" target="_blank"><img src="/images/Twitter.svg" alt="Twitter" height="28" width="28"></img></a>
					<a className="mx-1" href="https://discord.gg/q666a2sF6d" rel="noreferrer" target="_blank"><img src="/images/Discord.svg" alt="Discord" height="28" width="28"></img></a>
				</div>
			</div>
		</div>
	);
};

export default Footer;