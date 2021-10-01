// © XlXi 2021
// Graphictoria 5

import React from "react";
import { ConstructTime } from "../../Helpers/Date.js"

function scrollTo(scrollElement)
{
	window.scrollTo(0, document.getElementById(scrollElement).offsetTop - 70);
}

/*
Date can be calculated with this script:

var rn = new Date();
console.log(rn.getTime() - (rn.getTimezoneOffset() * 60 * 1000));

*/

const Legal = (props) => {
	var ClientDate = new Date();
	var LastModifiedDate = new Date(props.lastModified + (ClientDate.getTimezoneOffset() * 60 * 1000));
	
	return (
		<div className="container">
			<h2>{ props.name }</h2>
			<p>There are <b>{ props.sections.length }</b> sections on this page, you can use the buttons below to navigate. This page will { props.purpose }.</p>
			<ul className="mb-5">
				{
					props.sections.map(
						(section, index) => (
							<React.Fragment key={index}>
								{/* eslint-disable-next-line */}
								<li><a className="text-decoration-none" href="#" onClick={ () => scrollTo(`gtsection-${ section.id }`) }>{ section.title }</a></li>
							</React.Fragment>
						)
					)
				}
			</ul>

			{
				props.sections.map(
					(section, index) => (
						<React.Fragment key={index}>
							<h4 id={`gtsection-${ section.id }`}>{ section.title }</h4>
							{ section.content }
							<div className="mb-5">
							</div>
						</React.Fragment>
					)
				)
			}
			
			<h6 className="fw-bold mb-3">Effective as of { ConstructTime(LastModifiedDate) }</h6>
			<h5 className="text-danger">Graphictoria administrators will <b>NEVER</b> ask for your password. Your password is for <b>you and you only</b> to know.</h5>
		</div>
	);
}

export { Legal };