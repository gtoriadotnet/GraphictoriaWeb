// © XlXi 2021
// Graphictoria 5

import React from 'react';

export const Card = (props) => {
	return (
		<div className="container graphictoria-center-vh">
			<div className={`card graphictoria-small-card shadow-sm ${props.className}`}>
				<div className={`card-body ${props.padding? `p5r` : null} text-center`}>
					{ props.children }
				</div>
			</div>
		</div>
	);
};

export const CardTitle = (props) => {
	return (
		<>
			<h5 className="card-title fw-bold">{ props.children }</h5>
			<hr className="mx-5"/>
		</>
	);
};
