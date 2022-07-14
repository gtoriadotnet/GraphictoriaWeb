// © XlXi 2021
// Graphictoria 5

import { Component, createRef } from 'react';

import classNames from 'classnames/bind';

import axios from 'axios';

import Twemoji from 'react-twemoji';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

axios.defaults.withCredentials = true;

const shopCategories = {
	'Clothing': [
		{
			label: 'Hats',
			assetTypeId: 8
		},
		{
			label: 'Shirts',
			assetTypeId: 11
		},
		{
			label: 'T-Shirts',
			assetTypeId: 2
		},
		{
			label: 'Pants',
			assetTypeId: 12
		},
		{
			label: 'Package',
			assetTypeId: 32
		}
	],
	'Body Parts': [
		{
			label: 'Heads',
			assetTypeId: 17
		},
		{
			label: 'Faces',
			assetTypeId: 18
		},
		{
			label: 'Packages',
			assetTypeId: 32
		}
	],
	'Gear': [
		{
			label: 'Building',
			assetTypeId: 19,
			gearGenreId: 7
		},
		{
			label: 'Explosive',
			assetTypeId: 19,
			gearGenreId: 2
		},
		{
			label: 'Melee',
			assetTypeId: 19,
			gearGenreId: 0
		},
		{
			label: 'Musical',
			assetTypeId: 19,
			gearGenreId: 5
		},
		{
			label: 'Navigation',
			assetTypeId: 19,
			gearGenreId: 4
		},
		{
			label: 'Power Up',
			assetTypeId: 19,
			gearGenreId: 3
		},
		{
			label: 'Ranged',
			assetTypeId: 19,
			gearGenreId: 1
		},
		{
			label: 'Social',
			assetTypeId: 19,
			gearGenreId: 6
		},
		{
			label: 'Transport',
			assetTypeId: 19,
			gearGenreId: 8
		}
	]
};

function makeCategoryId(originalName, category) {
	return `shop-${originalName.toLowerCase().replaceAll(' ', '-')}-${category}`;
}

class ShopCategoryButton extends Component {
	constructor(props) {
		super(props);
		
		this.handleClick = this.handleClick.bind(this);
	}
	
	componentDidMount() {
		if (this.props.id === 'all') {
			let assetTypes = [];
			
			Object.keys(shopCategories).map((categoryName) => {
				let categoryAssetTypeIds = this.props.getCategoryAssetTypeIds(categoryName);
				
				switch(typeof(categoryAssetTypeIds.assetTypeId)) {
					case 'number':
						assetTypes[categoryAssetTypeIds.assetTypeId] = true;
						break;
					case 'object':
						categoryAssetTypeIds.assetTypeId.map((assetTypeId) => {
							assetTypes[assetTypeId] = true;
						});
						break;
				}
			});
			
			this.data = {assetTypeId: Object.keys(assetTypes)};
		} else if (this.props.id.startsWith('shop-all')) {
			this.data = this.props.getCategoryAssetTypeIds(this.props.categoryName);
		} else {
			this.data = this.props.getCategoryAssetTypeByLabel(this.props.categoryName, this.props.label);
		}
		
		if (this.props.id == 'shop-hats-clothing-type')
			this.props.navigateCategory(this.props.id, this.data);
	}
	
	handleClick() {
		this.props.navigateCategory(this.props.id, this.data);
	}
	
	render() {
		return (
			<a href="#"
			className={classNames({
				'text-decoration-none': true,
				'ms-2': (this.props.id != 'all'),
				'fw-bold': (this.props.shopState.selectedCategoryId == this.props.id)
			})}
			onClick={this.handleClick}>
				{ this.props.label }
			</a>
		);
	}
}

class ShopCategories extends Component {
	constructor(props) {
		super(props);
	}
	
	render() {
		return (
			<div className="graphictoria-shop-categories">
				<h5>Category</h5>
				<ShopCategoryButton id="all" label="All Items" getCategoryAssetTypeByLabel={this.props.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.props.getCategoryAssetTypeIds} navigateCategory={this.props.navigateCategory} shopState={this.props.shopState} />
				<ul className="list-unstyled ps-0">
					{
						Object.keys(shopCategories).map((categoryName, index) =>
							<li className="mb-1">
								<a className="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target={`#${makeCategoryId(categoryName, 'collapse')}`} aria-expanded={(index === 0 ? 'true' : 'false')} href="#">{ categoryName }</a>
								<div className={classNames({'collapse': true, 'show': (index === 0)})} id={makeCategoryId(categoryName, 'collapse')}>
									<ul className="btn-toggle-nav list-unstyled fw-normal small">
										<li><ShopCategoryButton id={makeCategoryId(`all-${categoryName}`, 'type')} label={`All ${categoryName}`} categoryName={categoryName} getCategoryAssetTypeByLabel={this.props.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.props.getCategoryAssetTypeIds} navigateCategory={this.props.navigateCategory} shopState={this.props.shopState} /></li>
										{
											shopCategories[categoryName].map(({label, assetTypeId, gearGenreId}, index) =>
												<li><ShopCategoryButton id={makeCategoryId(`${label}-${categoryName}`, 'type')} label={label} categoryName={categoryName} getCategoryAssetTypeByLabel={this.props.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.props.getCategoryAssetTypeIds} navigateCategory={this.props.navigateCategory} shopState={this.props.shopState} /></li>
											)
										}
									</ul>
								</div>
							</li>
						)
					}
				</ul>
			</div>
		);
	}
}

class Shop extends Component {
	constructor(props) {
		super(props);
		this.state = {
			selectedCategoryId: -1,
			pageItems: [],
			pageLoaded: true,
			pageNumber: null,
			pageCount: null,
			error: false
		};
		
		this.navigateCategory = this.navigateCategory.bind(this);
	}
	
	getCategoryAssetTypeIds(categoryName) {
		let assetTypes = [];
		
		shopCategories[categoryName].map(({assetTypeId}) => {
			assetTypes[assetTypeId] = true;
		});
		
		assetTypes = Object.keys(assetTypes);
		
		if (assetTypes.length == 1)
			assetTypes = assetTypes[0];
		
		return {assetTypeId: assetTypes};
	}
	
	getCategoryAssetTypeByLabel(categoryName, label) {
		let assetType = -1;
		
		shopCategories[categoryName].map((sort) => {
			if (sort.label === label) {
				assetType = sort;
			}
		});
		
		return assetType;
	}
	
	navigateCategory(categoryId, data) {
		this.setState({selectedCategoryId: categoryId, pageLoaded: false});
		
		let url = buildGenericApiUrl('api', 'catalog/v1/list-json');
		let paramIterator = 0;
		Object.keys(data).filter(key => {
			if (key == 'label')
				return false;
			return true;
		}).map(key => {
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
				this.inputBox.current.focus();
			});
	}
	
	render() {
		return (
			<div className="container-lg my-2">
				<div className="row">
					<div className="col d-flex">
						<h4 className="my-auto">Shop</h4>
					</div>
					<div className="col-lg-5 my-2 my-lg-0 mb-lg-2 d-flex">
						<button className="btn btn-secondary me-2"><i className="fa-solid fa-gear"></i></button>
						<div className="input-group">
							<input type="text" className="form-control d-lg-flex" placeholder="Item name" />
							<button className="btn btn-primary">Search</button>
						</div>
					</div>
				</div>
				<div className="row">
					<div className="col-md-2">
						<ShopCategories getCategoryAssetTypeByLabel={this.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.getCategoryAssetTypeIds} navigateCategory={this.navigateCategory} shopState={this.state} />
					</div>
					<div className="col-md-10 d-flex flex-column">
						<div className="card p-3">
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
										this.state.pageItems.map(({Name, Creator, Thumbnail}, index) =>
											<a className="graphictoria-item-card" href="#" key={ index }>
												<span className="card m-2">
													<img className="img-fluid" src={ Thumbnail } />
													<div className="p-2">
														<p>{ Name }</p>
														<p className="text-muted">Free</p>
													</div>
												</span>
											</a>
										)
									}
								</div>
							}
						</div>
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
			</div>
		);
	}
}

export default Shop;