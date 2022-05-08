// © XlXi 2021
// Graphictoria 5

import { useState, useRef } from 'react';

import Loader from './Loader';

const Feed = () => {
	const inputRef = useRef();
	const submitRef = useRef();
	const feedRef = useRef();
	const [feedLoaded, setFeedLoaded] = useState(false);
	
	return (
		<>
			<h4>My Feed</h4>
			<div className="card mb-2">
				<div className="input-group p-2">
					<input ref={ inputRef } type="text" className="form-control" placeholder="What are you up to?" />
					<button ref={ submitRef } className="btn btn-secondary" type="submit">Share</button>
				</div>
			</div>
			<div ref={ feedRef } className="d-flex">
				{
					feedLoaded ?
					
					<h1>feed</h1>
					:
					<Loader />
				}
			</div>
		</>
	);
};

export default Feed;