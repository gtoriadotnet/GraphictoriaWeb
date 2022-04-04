// © XlXi 2021
// Graphictoria 5

import React from 'react';

/* regular card */
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

export const BigCard = (props) => {
	return (
		<div className="container graphictoria-center-vh">
			<div className={`card shadow-sm ${props.className}`}>
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

/* mini card */
export const MiniCardTitle = (props) => {
	return (
		<>
			<h6 className='card-title'>{ props.children }</h6>
			<hr className='mx-5 my-0 mb-2' />
		</>
	);
};

export const MiniCard = (props) => {
	return (
		<div className={`card${props.className ? ' ' + props.className : ''}`}>
			<div className='card-body p-2 text-center'>
				{ props.children }
			</div>
		</div>
	);
};
