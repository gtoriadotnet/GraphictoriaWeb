// © XlXi 2021
// Graphictoria 5

import { useState, useRef, useEffect } from 'react';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

let posts = [
	{
		postId: 0,
		poster: {
			type: "User",
			name: "XlXi",
			thumbnail: "https://www.gtoria.local/images/testing/headshot.png"
		},
		content: "test",
		time: "Now"
	},
	{
		postId: 1,
		poster: {
			type: "User",
			name: "XlXi",
			thumbnail: "https://www.gtoria.local/images/testing/headshot.png"
		},
		content: "test 2",
		time: "Now"
	}
];

const Feed = () => {
	const inputRef = useRef();
	const submitRef = useRef();
	const feedRef = useRef();
	const [feedLoaded, setFeedLoaded] = useState(true);
	const [mouseHover, setMouseHover] = useState(-1);
	
	useEffect(() => {
		
	});
	
	return (
		<>
			<h4>My Feed</h4>
			<div className="card mb-2">
				<div className="input-group p-2">
					<input ref={ inputRef } type="text" className="form-control" placeholder="What are you up to?" />
					<button ref={ submitRef } className="btn btn-secondary" type="submit">Share</button>
				</div>
			</div>
			{
				feedLoaded ?
				(
					posts.length > 0 ?
					<div className="card d-flex">
					{
						posts.map(({ postId, poster, time, content }, index) => 
							<>
								<div className="row p-2" onMouseEnter={ () => setMouseHover(index) } onMouseLeave={ () => setMouseHover(-1) }>
									<div className="col-3 col-sm-2 col-md-1">
										<a href="#">
											<img src={ poster.thumbnail } className="img-fluid border graphictora-user-circle" />
										</a>
									</div>
									<div className="col-9 col-sm-10 col-md-11">
										<div className="d-flex">
											<a href="#" className="text-decoration-none fw-bold me-auto">{ poster.name } <i className="fa-solid fa-gavel"></i></a>
											{ mouseHover == index ? <a href={ buildGenericApiUrl('www', `report/user-wall/${postId}`) } target="_blank" className="text-decoration-none link-danger me-2">Report <i className="fa-solid fa-circle-exclamation"></i></a> : null }
											<p className="text-muted">{ time }</p>
										</div>
										<p>{ content }</p>
									</div>
								</div>
								{ posts.length != (index+1) ? <hr className="m-0" /> : null }
							</>
						)
					}
					</div>
					:
					<div className="text-center mt-3">
						<p className="text-muted">No posts were found. You could be the first!</p>
					</div>
				)
				:
				<div className="d-flex">
					<Loader />
				</div>
			}
		</>
	);
};

export default Feed;