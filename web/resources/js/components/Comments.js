// © XlXi 2022
// Graphictoria 5

import { Component } from 'react';

import Loader from './Loader';

class Comments extends Component {
	constructor(props) {
		super(props);
	}
	
	render() {
		return (
			<>
				<h4 className="pt-3">Comments</h4>
				<div className="card mb-2">
					<div className="input-group p-2">
						<input disabled="disabled" type="text" className="form-control" placeholder="Write a comment!" />
						<button disabled="disabled" type="submit" className="btn btn-secondary">Share</button>
					</div>
				</div>
				<div className="d-flex">
					<Loader />
				</div>
			</>
		);
	}
}

export default Comments;