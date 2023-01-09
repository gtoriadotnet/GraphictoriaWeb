/*
	Copyright © XlXi 2022
*/

import { Component, createRef } from 'react';
import axios from 'axios';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

axios.defaults.withCredentials = true;

class SummaryTab extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loaded: false,
			filters: [
				{label: 'Past Day', tag: 'pastday', ref: createRef()},
				{label: 'Past Week', tag: 'pastweek', ref: createRef()},
				{label: 'Past Month', tag: 'pastmonth', ref: createRef()},
				{label: 'Past Year', tag: 'pastyear', ref: createRef()},
				{label: 'All Time', tag: 'all', ref: createRef()}
			],
			items: [],
			total: null
		};
		
		this.categoryDropdown = createRef();
		
		this.setTag = this.setTag.bind(this);
	}
	
	componentDidMount() {
		this.setTag('pastday');
	}
	
	setTag(tag) {
		this.setState({ loaded: false });
		
		let selectedTag = this.state.filters.find(obj => {
			return obj.tag === tag
		});
		this.categoryDropdown.current.innerText = selectedTag.label;
		
		this.state.filters.map(({ label, tag, ref }) => {
			if(ref.current.innerText == selectedTag.label)
				ref.current.classList.add('active');
			else
				ref.current.classList.remove('active');
		});
		
		axios.get(buildGenericApiUrl('api', `shop/v1/user-summary?filter=${tag}`))
			.then(res => {
				const response = res.data;
				this.setState({ items: response.columns, total: response.total, loaded: true });
			});
	}
	
	render() {
		return (
			<div>
				<p className="d-inline-block me-2">Time Period:</p>
				<button className="btn btn-secondary dropdown-toggle" type="button" disabled={ !this.state.loaded } ref={ this.categoryDropdown } data-bs-toggle="dropdown" aria-expanded="false">
					...
				</button>
				<ul className="dropdown-menu">
					{
						this.state.filters.map(({ label, tag, ref }) => 
							<li><button className="dropdown-item" ref={ ref } onClick={ () => this.setTag(tag) }>{ label }</button></li>
						)
					}
				</ul>
				{
					this.state.loaded
					?
					<>
						<p className="virtubrick-tokens">Tokens</p>
						<table className="table virtubrick-table mt-2">
							<thead>
								<tr>
									<th scope="col">Categories</th>
									<th scope="col">Credit</th>
								</tr>
							</thead>
							<tbody>
								{
									this.state.items.map(({ name, total }, index) => 
										<tr className="align-middle">
											<th scope="col">{ name }</th>
											<th scope="col">{ total != 0 ? <span className="virtubrick-tokens">{ total }</span> : null }</th>
										</tr>
									)
								}
							</tbody>
						</table>
						<p className="text-right">Total <span className="virtubrick-tokens">{ this.state.total }</span></p>
					</>
					:
					<div className="d-flex">
						<Loader />
					</div>
				}
			</div>
		)
	}
}

class TransactionsTab extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loaded: false,
			loadingMore: false,
			filters: [
				{label: 'Purchases', tag: 'purchases', ref: createRef()},
				{label: 'Sales', tag: 'sales', ref: createRef()},
				{label: 'Commissions', tag: 'commissions', ref: createRef()},
				{label: 'Group Payouts', tag: 'grouppayouts', ref: createRef()}
			],
			items: []
		};
		
		this.categoryDropdown = createRef();
		
		this.setTag = this.setTag.bind(this);
		this.loadMore = this.loadMore.bind(this);
	}
	
	componentWillMount() {
		window.addEventListener('scroll', this.loadMore);
	}

	componentWillUnmount() {
		window.removeEventListener('scroll', this.loadMore);
	}
	
	componentDidMount() {
		this.setTag('purchases');
	}
	
	setTag(tag) {
		this.setState({ loaded: false });
		
		let selectedTag = this.state.filters.find(obj => {
			return obj.tag === tag
		});
		this.categoryDropdown.current.innerText = selectedTag.label;
		
		this.state.filters.map(({ label, tag, ref }) => {
			if(ref.current.innerText == selectedTag.label)
				ref.current.classList.add('active');
			else
				ref.current.classList.remove('active');
		});
		
		this.nextCursor = null;
		
		axios.get(buildGenericApiUrl('api', `shop/v1/user-transactions?filter=${tag}`))
			.then(res => {
				const response = res.data;
				
				this.nextCursor = response.next_cursor;
				this.setState({ items: response.data, filter: tag, loaded: true });
			});
	}
	
	loadMore() {
		// XlXi: Taking the height of the footer into account.
		if (window.innerHeight + document.documentElement.scrollTop >= document.scrollingElement.scrollHeight-200) {
			if (!!(this.nextCursor) && !this.state.loading && !this.state.loadingMore) {
				this.setState({ loadingMore: true });
				
				axios.get(buildGenericApiUrl('api', `shop/v1/user-transactions?filter=${this.state.filter}&cursor=${this.nextCursor}`))
					.then(res => {
						const response = res.data;
						
						this.nextCursor = response.next_cursor;
						this.setState({ items: this.state.items.concat(response.data), loadingMore: false });
					});
			}
		}
	}
	
	render() {
		return (
			<div>
				<p className="d-inline-block me-2">Currently Selected:</p>
				<button className="btn btn-secondary dropdown-toggle" type="button" disabled={ !this.state.loaded } ref={ this.categoryDropdown } data-bs-toggle="dropdown" aria-expanded="false">
					...
				</button>
				<ul className="dropdown-menu">
					{
						this.state.filters.map(({ label, tag, ref }) => 
							<li><button className="dropdown-item" ref={ ref } onClick={ () => this.setTag(tag) }>{ label }</button></li>
						)
					}
				</ul>
				{
					this.state.loaded
					?
					<>
						<p className="virtubrick-tokens">Tokens</p>
						<table className="table virtubrick-table mt-2">
							<thead>
								<tr>
									<th scope="col">Date</th>
									<th scope="col">Member</th>
									<th scope="col">Description</th>
									<th scope="col">Amount</th>
								</tr>
							</thead>
							<tbody>
								{
									this.state.items.map(({ date, member, description, amount, item }, index) => 
										<tr className="align-middle">
											<th scope="col">{ date }</th>
											<th scope="col"><a href={ member.url } className="text-decoration-none">{ member.name }</a></th>
											<th scope="col">{ description }{ item != null ? <a href={ item.url } className="text-decoration-none">{ item.name }</a> : null }</th>
											<th scope="col"><p className="virtubrick-tokens">{ amount }</p></th>
										</tr>
									)
								}
							</tbody>
						</table>
						{
							this.state.loadingMore
							?
							<div className="d-flex">
								<Loader />
							</div>
							:
							null
						}
					</>
					:
					<div className="d-flex">
						<Loader />
					</div>
				}
			</div>
		)
	}
}

class Transactions extends Component {
	constructor(props) {
		super(props);
		this.state = {
			loaded: false,
			tabs: [
				{label: 'Summary', name: 'summary', ref: createRef()},
				{label: 'My Transactions', name: 'transactions', ref: createRef()}
			]
		};
		
		this.tabIndex = 0;
		
		this.setCurrentTab = this.setCurrentTab.bind(this);
		this.setTab = this.setTab.bind(this);
	}
	
	componentDidMount() {
		const params = new Proxy(new URLSearchParams(window.location.search), {
			get: (searchParams, prop) => searchParams.get(prop),
		});
		let queryTab = this.state.tabs.find(obj => {
			if(!params.tab)
				return false;
			
			return obj.name === params.tab.toLowerCase();
		});
		
		if(queryTab)
			this.setTab(queryTab.name);
		else
			this.setTab('summary');
	}
	
	setCurrentTab(instance)
	{
		this.currentTab = instance;
		this.setState({loaded: true});
	}
	
	setTab(tabType) {
		this.setState({loaded: false});
		this.tabIndex += 1;
		
		switch(tabType)
		{
			case 'summary':
				this.setCurrentTab(<SummaryTab key={this.tabIndex} />);
				break;
			case 'transactions':
				this.setCurrentTab(<TransactionsTab key={this.tabIndex} />);
				break;
		}
		
		this.state.tabs.map(({ name, ref }) => {
			if(name == tabType)
				ref.current.classList.add('active');
			else
				ref.current.classList.remove('active');
		});
	}
	
	render() {
		return (
			<>
				<ul className="nav nav-tabs">
					{
						this.state.tabs.map(({ label, name, ref }) => 
							<li className="nav-item">
								<button className="nav-link" onClick={ () => this.setTab(name) } ref={ ref }>{ label }</button>
							</li>
						)
					}
				</ul>
				<div className="card p-2">
					{ this.state.loaded ? this.currentTab : <Loader /> }
				</div>
			</>
		);
	}
}

export default Transactions;