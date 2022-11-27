/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import { Component, createRef } from 'react';

import classNames from 'classnames/bind';

import axios from 'axios';

import Twemoji from 'react-twemoji';

import { buildGenericApiUrl } from '../util/HTTP.js';
import ProgressiveImage from './ProgressiveImage';
import Loader from './Loader';

axios.defaults.withCredentials = true;

function commaSeparate(num) {
	let str = num.toString().split('.');
	str[0] = str[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	return str.join('.');
}

class GameItemCard extends Component {
	constructor(props) {
		super(props);
		this.state = {
			hovered: false
		}
	}
	
	render() {
		return (
			<a
				className="graphictoria-item-card graphictoria-game-card"
				href={ this.props.item.Url }
				onMouseEnter={() => this.setState({hovered: true})}
				onMouseLeave={() => this.setState({hovered: false})}
			>
				<span className="card m-2">
					<ProgressiveImage
						src={ buildGenericApiUrl('www', 'images/busy/game-icon.png') } 
						placeholderImg={ buildGenericApiUrl('www', 'images/busy/game-icon.png') }
						alt={ this.props.item.Name }
						className='img-fluid'
					/>
					<div className="p-2">
						<p>{ this.props.item.Name }</p>
						<p className="text-muted small">{commaSeparate(this.props.item.Playing)} Playing</p>
						<div className="d-flex mt-1">
							<i className={classNames({
								'fa-solid': true,
								'fa-thumbs-up': true,
								'text-success': this.state.hovered
							})}></i>
							<div className={classNames({
								'my-auto': true,
								'mx-1': true,
								'graphictoria-vote-bar': true,
								'rounded-1': true,
								'border': true,
								'border-light': true,
								'position-relative': true,
								'flex-fill': true,
								'bg-secondary': !this.state.hovered,
								'bg-danger': this.state.hovered
							})}>
								<div className={classNames({
									'rounded-1': true,
									'position-absolute': true,
									'bg-dark': !this.state.hovered,
									'bg-success': this.state.hovered,
								})}
								style={{width: (this.props.item.Ratio * 100) + '%', height: '8px'}}></div>
							</div>
							<i className={classNames({
								'fa-solid': true,
								'fa-thumbs-down': true,
								'text-danger': this.state.hovered
							})}></i>
						</div>
					</div>
				</span>
				{
					this.state.hovered ?
					<span className="graphictoria-item-details">
						<div className="card px-2">
							<hr className="m-0" />
							<p className="text-truncate my-1">
								<span className="text-muted">By </span><a href={ this.props.item.Creator.Url } className="text-decoration-none fw-normal">{ this.props.item.Creator.Name }</a>
							</p>
						</div>
					</span>
					:
					null
				}
			</a>
		);
	}
}

class Games extends Component {
	constructor(props) {
		super(props);
		this.state = {
			pageItems: [],
			pageLoaded: true,
			pageNumber: null,
			pageCount: null,
			error: false
		};
		
		this.navigate = this.navigate.bind(this);
	}
	
	componentDidMount() {
		this.navigate();
	}
	
	navigate(data = {}) {
		this.setState({pageLoaded: false});
		
		let url = buildGenericApiUrl('api', 'games/v1/list-json');
		let paramIterator = 0;
		Object.keys(data).map(key => {
			url += ((paramIterator++ == 0 ? '?' : '&') + `${key}=${data[key]}`);
		});
		
		axios.get(url)
			.then(res => {
				const items = res.data;
				
				this.setState({ pageItems: items.data, pageCount: items.pages, pageNumber: 1, pageLoaded: true, error: false });
			}).catch(err => {
				const data = err.response.data;
				
				let errorMessage = 'An error occurred while processing your request.';
				if(data.errors)
					errorMessage = data.errors[0].message;
				
				this.setState({ pageItems: [], pageCount: 1, pageNumber: 1, pageLoaded: true, error: errorMessage });
			});
	}
	
	render() {
		return (
			<div className="container-sm my-2 d-flex flex-column">
				<h4 className="my-auto">Games</h4>
				<div className="card p-3 mt-2">
					{
						this.state.error ?
						<div className="alert alert-danger p-2 mb-0 text-center">{this.state.error}</div>
						:
						null
					}
					{
						!this.state.pageLoaded ?
						<div className="graphictoria-shop-overlay">
							<Loader />
						</div>
						:
						null
					}
					{
						(this.state.pageItems.length == 0 && !this.state.error) ?
						<p className="text-muted text-center">Nothing found.</p>
						:
						<div>
							{
								this.state.pageItems.map((item, index) =>
									<GameItemCard item={ item } key={ index } />
								)
							}
						</div>
					}
					{
						this.state.pageCount > 1 ?
						<ul className="list-inline mx-auto mt-3">
							<li className="list-inline-item">
								<button className="btn btn-secondary" disabled={(this.state.pageNumber <= 1) ? true : null}><i className="fa-solid fa-angle-left"></i></button>
							</li>
							<li className="list-inline-item graphictoria-paginator">
								<span>Page&nbsp;</span>
								<input type="text" value={ this.state.pageNumber || '' } className="form-control" disabled={this.state.pageLoaded ? null : true} />
								<span>&nbsp;of { this.state.pageCount || '???' }</span>
							</li>
							<li className="list-inline-item">
								<button className="btn btn-secondary" disabled={(this.state.pageNumber >= this.state.pageCount) ? true : null}><i className="fa-solid fa-angle-right"></i></button>
							</li>
						</ul>
						:
						null
					}
				</div>
			</div>
		);
	}
}

export default Games;