/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import { Component, createRef } from 'react';
import axios from 'axios';
import Twemoji from 'react-twemoji';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

const commentsId = 'gt-comments'; // XlXi: Keep this in sync with the Item page.

axios.defaults.withCredentials = true;

class Comments extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loaded: false,
			loadingCursor: false,
			disabled: false,
			error: '',
			comments: [],
			mouseHover: -1
		};
		
		this.inputBox = createRef();
		
		this.loadComments = this.loadComments.bind(this);
		this.loadMore = this.loadMore.bind(this);
		this.postComment = this.postComment.bind(this);
	}
	
	componentWillMount() {
		window.addEventListener('scroll', this.loadMore);
	}

	componentWillUnmount() {
		window.removeEventListener('scroll', this.loadMore);
	}
	
	componentDidMount() {
		let commentsElement = document.getElementById(commentsId);
		if (commentsElement) {
			this.assetId = commentsElement.getAttribute('data-asset-id')
			this.setState({
				canComment: (commentsElement.getAttribute('data-can-comment') === '1')
			});
		}
		
		this.loadComments();
	}
	
	loadComments() {
		axios.get(buildGenericApiUrl('api', `comments/v1/list-json?assetId=${this.assetId}`))
			.then(res => {
				const comments = res.data;
				
				this.nextCursor = comments.next_cursor;
				this.setState({ comments: comments.data, loaded: true });
			});
	}
	
	// XlXi: https://stackoverflow.com/questions/57778950/how-to-load-more-search-results-when-scrolling-down-the-page-in-react-js
	loadMore() {
		// XlXi: Taking the height of the footer into account.
		if (window.innerHeight + document.documentElement.scrollTop >= document.scrollingElement.scrollHeight-200) {
			if (!!(this.nextCursor) && !this.state.loadingCursor) {
				this.setState({ loadingCursor: true });
				
				axios.get(buildGenericApiUrl('api', `comments/v1/list-json?assetId=${this.assetId}&cursor=${this.nextCursor}`))
					.then(res => {
						const comments = res.data;
						
						this.nextCursor = comments.next_cursor;
						this.setState({ comments: this.state.comments.concat(comments.data), loadingCursor: false });
					});
			}
		}
	}
	
	postComment() {
		this.setState({ disabled: true });
		
		const postText = this.inputBox.current.value;
		if (postText == '') {
			this.setState({ disabled: false, error: 'Your comment cannot be blank.' });
			this.inputBox.current.focus();
			return;
		}
		
		axios.post(buildGenericApiUrl('api', `comments/v1/share`), { assetId: this.assetId, content: postText })
			.then(res => {
				this.inputBox.current.value = '';
				this.setState({ loaded: false, loadingCursor: false, disabled: false });
				this.loadComments();
			})
			.catch(err => {
				const data = err.response.data;
				
				this.setState({ disabled: false, error: data.message });
				this.inputBox.current.focus();
			});
	}
	
	render() {
		return (
			<>
				<h4 className="pt-3">Comments</h4>
				{
					this.state.canComment != false
					?
					<div className="card mb-2">
						{
							this.state.error != ''
							?
							<div className="alert alert-danger graphictoria-alert graphictoria-error-popup m-2 mb-0">{ this.state.error }</div>
							:
							null
						}
						<div className="input-group p-2">
							<input disabled={ (!this.state.loaded || this.state.disabled) } type="text" className="form-control" placeholder="Write a comment!" ref={ this.inputBox }/>
							<button disabled={ (!this.state.loaded || this.state.disabled) } type="submit" className="btn btn-secondary" onClick={ this.postComment }>Post</button>
						</div>
					</div>
					:
					null
				}
				{
					this.state.loaded
					?
					(
						this.state.comments.length > 0
						?
						<>
							<div className="card">
							{
								this.state.comments.map(({ commentId, poster, time, content }, index) => 
									<>
										<div className="d-flex p-2" onMouseEnter={ () => this.setState({ mouseHover: index }) } onMouseLeave={ () => this.setState({ mouseHover: -1 }) }>
											<div className="me-2">
												<a href={ poster.url }>
													<img src={ poster.thumbnail } alt={ poster.name } width="50" height="50" className="border graphictora-feed-user-circle" />
												</a>
											</div>
											<div className="flex-fill">
												<div className="d-flex">
													<a href={ poster.url } className="text-decoration-none me-auto fw-bold">{ poster.name }{ poster.icon ? <>&nbsp;<i className={ poster.icon }></i></> : null }</a>
													{ this.state.mouseHover == index ? <a href={ buildGenericApiUrl('www', `report/comment/${commentId}`) } target="_blank" className="text-decoration-none link-danger me-2">Report <i className="fa-solid fa-circle-exclamation"></i></a> : null }
													<p className="text-muted">{ time }</p>
												</div>
												<Twemoji options={{ className: 'twemoji', base: '/images/twemoji/', folder: 'svg', ext: '.svg' }}>
													<p>{ content }</p>
												</Twemoji>
											</div>
										</div>
										{ this.state.comments.length != (index+1) ? <hr className="m-0" /> : null }
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
							<p className="text-muted">No comments were found. { this.state.canComment ? 'You could be the first!' : null }</p>
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

export default Comments;