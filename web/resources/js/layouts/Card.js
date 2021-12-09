// © XlXi 2021
// Graphictoria 5

import React from 'react';

const Card = (props) => {
	return (
		<div className="container graphictoria-center-vh">
			<div className="card graphictoria-small-card shadow-sm">
				<div className="card-body text-center">
					{ props.children }
				</div>
			</div>
		</div>
	);
};

const CardTitle = (props) => {
	return (
		<>
			<h5 className="card-title fw-bold">{ props.children }</h5>
			<hr className="mx-5"/>
		</>
	);
};

export {
	Card,
	CardTitle
};