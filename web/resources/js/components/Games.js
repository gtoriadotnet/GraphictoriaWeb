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
				className="graphictoria-item-card"
				href="#"
				onMouseEnter={() => this.setState({hovered: true})}
				onMouseLeave={() => this.setState({hovered: false})}
			>
				<span className="card m-2">
					<ProgressiveImage
						src={ buildGenericApiUrl('www', 'images/busy/game.png') } 
						placeholderImg={ buildGenericApiUrl('www', 'images/busy/game.png') }
						alt="Game todo"
						className='img-fluid'
					/>
					<div className="p-2">
						<p>Todo</p>
						<p className="text-muted small">{commaSeparate(1337)} Playing</p>
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
								style={{width: '80%', height: '8px'}}></div>
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
								<span className="text-muted">By </span><a href="#" className="text-decoration-none fw-normal">Todo</a>
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
	}
	
	render() {
		return (
			<div className="container-lg my-2 d-flex flex-column">
				<h4 className="my-auto">Games</h4>
				<Loader />
				<GameItemCard />
			</div>
		);
	}
}

export default Games;