// © XlXi 2021
// Graphictoria 5

import { Component } from 'react';

import axios from 'axios';

import Twemoji from 'react-twemoji';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

axios.defaults.withCredentials = true;

class Feed extends Component {
	constructor(props) {
		super(props);
		this.state = {
			feedLoaded: false,
			loadingCursor: false,
			feedPosts: [],
			mouseHover: -1
		};
		
		this.loadMore = this.loadMore.bind(this);
	}
	
	componentWillMount() {
		window.addEventListener('scroll', this.loadMore);
	}

	componentWillUnmount() {
		window.removeEventListener('scroll', this.loadMore);
	}
	
	componentDidMount() {
		axios.get(buildGenericApiUrl('api', 'feed/v1/list-json'))
			.then(res => {
				const posts = res.data;
				
				this.nextCursor = posts.next_cursor;
				this.setState({ feedPosts: posts.data, feedLoaded: true });
			});
	}
	
	// XlXi: https://stackoverflow.com/questions/57778950/how-to-load-more-search-results-when-scrolling-down-the-page-in-react-js
	loadMore() {
		// XlXi: Taking the height of the footer into account.
		if (window.innerHeight + document.documentElement.scrollTop >= document.scrollingElement.scrollHeight-200) {
			if (!!(this.nextCursor) && !this.state.loadingCursor) {
				this.setState({ loadingCursor: true });
				
				axios.get(buildGenericApiUrl('api', `feed/v1/list-json?cursor=${this.nextCursor}`))
					.then(res => {
						const posts = res.data;
						
						this.nextCursor = posts.next_cursor;
						this.setState({ feedPosts: this.state.feedPosts.concat(posts.data), loadingCursor: false });
					});
			}
		}
	}
	
	render() {
		return (
			<>
				<h4>My Feed</h4>
				<div className="card mb-2">
					<div className="input-group p-2">
						<input disabled={ !this.state.feedLoaded } type="text" className="form-control" placeholder="What are you up to?" />
						<button disabled={ !this.state.feedLoaded } type="submit" className="btn btn-secondary">Share</button>
					</div>
				</div>
				{
					this.state.feedLoaded ?
					(
						this.state.feedPosts.length > 0 ?
						<>
							<div className="card d-flex">
							{
								this.state.feedPosts.map(({ postId, poster, time, content }, index) => 
									<>
										<div className="row p-2" onMouseEnter={ () => this.setState({ mouseHover: index }) } onMouseLeave={ () => this.setState({ mouseHover: -1 }) }>
											<div className="col-3 col-sm-2 col-md-1">
												<a href={ buildGenericApiUrl('www', (poster.type == 'User' ? `users/${poster.name}/profile` : `groups/${poster.id}`)) }>
													{ poster.type == 'User' ?
														<img src={ poster.thumbnail } width="90" height="90" className="img-fluid border graphictora-user-circle" /> :
														<img src={ poster.thumbnail } width="90" height="90" className="img-fluid" />
													}
												</a>
											</div>
											<div className="col-9 col-sm-10 col-md-11">
												<div className="d-flex">
													<a href={ buildGenericApiUrl('www', (poster.type == 'User' ? `users/${poster.name}/profile` : `groups/${poster.id}`)) } className="text-decoration-none fw-bold me-auto">{ poster.name }{ poster.icon ? <>&nbsp;<i className={ poster.icon }></i></> : null }</a>
													{ this.state.mouseHover == index ? <a href={ buildGenericApiUrl('www', `report/user-wall/${postId}`) } target="_blank" className="text-decoration-none link-danger me-2">Report <i className="fa-solid fa-circle-exclamation"></i></a> : null }
													<p className="text-muted">{ time }</p>
												</div>
												<Twemoji options={{ className: 'twemoji', base: '/images/twemoji/', folder: 'svg', ext: '.svg' }}>
													<p>{ content }</p>
												</Twemoji>
											</div>
										</div>
										{ this.state.feedPosts.length != (index+1) ? <hr className="m-0" /> : null }
									</>
								)
							}
							</div>
							{
								this.state.loadingCursor ?
								<div className="d-flex mt-2">
									<Loader />
								</div>
								:
								null
							}
						</>
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
	}
}

export default Feed;