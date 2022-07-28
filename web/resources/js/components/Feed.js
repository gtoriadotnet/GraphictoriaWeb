// © XlXi 2022
// Graphictoria 5

import { Component, createRef } from 'react';

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
			feedDisabled: false,
			loadingCursor: false,
			error: '',
			feedPosts: [],
			mouseHover: -1
		};
		
		this.inputBox = createRef();
		
		// XlXi: Thanks, React.
		this.loadPosts = this.loadPosts.bind(this);
		this.loadMore = this.loadMore.bind(this);
		this.sharePost = this.sharePost.bind(this);
	}
	
	componentWillMount() {
		window.addEventListener('scroll', this.loadMore);
	}

	componentWillUnmount() {
		window.removeEventListener('scroll', this.loadMore);
	}
	
	componentDidMount() {
		this.loadPosts();
	}
	
	loadPosts() {
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
	
	sharePost() {
		this.setState({ feedDisabled: true });
		
		const postText = this.inputBox.current.value;
		if (postText == '') {
			this.setState({ feedDisabled: false, error: 'Your shout cannot be blank.' });
			this.inputBox.current.focus();
			return;
		}
		
		axios.post(buildGenericApiUrl('api', `feed/v1/share`), { content: postText })
			.then(res => {
				this.inputBox.current.value = '';
				this.setState({ feedLoaded: false, loadingCursor: false, feedDisabled: false });
				this.loadPosts();
			})
			.catch(err => {
				const data = err.response.data;
				
				this.setState({ feedDisabled: false, error: data.message });
				this.inputBox.current.focus();
			});
	}
	
	render() {
		return (
			<>
				<h4>My Feed</h4>
				<div className="card mb-2">
					{
						this.state.error != ''
						?
						<div className="alert alert-danger graphictoria-alert graphictoria-error-popup m-2 mb-0">{ this.state.error }</div>
						:
						null
					}
					<div className="input-group p-2">
						<input disabled={ (!this.state.feedLoaded || this.state.feedDisabled) } type="text" className={ `form-control${this.state.error != '' ? ' is-invalid' : ''}` } placeholder="What are you up to?" onChange={ () => this.setState({ error: '' }) } ref={ this.inputBox } />
						<button disabled={ (!this.state.feedLoaded || this.state.feedDisabled) } type="submit" className="btn btn-secondary" onClick={ this.sharePost }>Share</button>
					</div>
				</div>
				{
					this.state.feedLoaded ?
					(
						this.state.feedPosts.length > 0 ?
						<>
							<div className="card">
							{
								this.state.feedPosts.map(({ postId, poster, time, content }, index) => 
									<>
										<div className="d-flex p-2" onMouseEnter={ () => this.setState({ mouseHover: index }) } onMouseLeave={ () => this.setState({ mouseHover: -1 }) }>
											<div className="me-2">
												<a href={ buildGenericApiUrl('www', (poster.type == 'User' ? `users/${poster.name}/profile` : `groups/${poster.id}`)) }>
													{ poster.type == 'User' ?
														<img src={ poster.thumbnail } alt={ poster.name } width="50" height="50" className="border graphictora-feed-user-circle" /> :
														<img src={ poster.thumbnail } alt={ poster.name } width="50" height="50" className="img-fluid" />
													}
												</a>
											</div>
											<div className="flex-fill">
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