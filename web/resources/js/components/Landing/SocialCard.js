// © XlXi 2021
// Graphictoria 5

import React from 'react';

const SocialCard = (props) => {
	return (
		<div className="col-lg-4 mb-4 d-flex flex-column align-items-center">
			<svg className="rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label={ props.title }><image width="100%" height="100%" href={ '/images/social/' + props.title.toLowerCase() + '.png' }></image></svg>
			<h2 className="mt-3">{ props.title }</h2>
			<p>{ props.description }</p>
			<a className="btn btn-primary mt-auto" href={ props.link } rel="noreferrer" target="_blank" role="button">View »</a>
		</div>
	);
};

export default SocialCard;