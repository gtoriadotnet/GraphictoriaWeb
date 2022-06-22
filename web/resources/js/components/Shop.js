// © XlXi 2021
// Graphictoria 5

import { Component, createRef } from 'react';

import axios from 'axios';

import Twemoji from 'react-twemoji';

import { buildGenericApiUrl } from '../util/HTTP.js';
import Loader from './Loader';

axios.defaults.withCredentials = true;

const shopCategories = [
	['Clothing'] = [
		{
			label: 'Hats',
			assetTypeId: 0
		}
	]
];

class Shop extends Component {
	constructor(props) {
		super(props);
		this.state = {
			
		};
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
						<div className="graphictoria-shop-categories">
							<h5>Category</h5>
							<a href="#" className="text-decoration-none">All Items</a>
							<ul className="list-unstyled ps-0">
								<li className="mb-1">
									<a className="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-clothing-collapse" aria-expanded="true" href="#">Clothing</a>
									<div className="collapse show" id="shop-clothing-collapse">
										<ul className="btn-toggle-nav list-unstyled fw-normal small">
											<li><a href="#" className="text-decoration-none ms-2">All Clothing</a></li>
											<li><a href="#" className="fw-bold text-decoration-none ms-2">Hats</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Shirts</a></li>
											<li><a href="#" className="text-decoration-none ms-2">T-Shirts</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Pants</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Packages</a></li>
										</ul>
									</div>
								</li>
								<li className="mb-1">
									<a className="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-bodyparts-collapse" aria-expanded="false" href="#">Body Parts</a>
									<div className="collapse" id="shop-bodyparts-collapse">
										<ul className="btn-toggle-nav list-unstyled fw-normal small">
											<li><a href="#" className="text-decoration-none ms-2">All Body Parts</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Heads</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Faces</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Packages</a></li>
										</ul>
									</div>
								</li>
								<li className="mb-1">
									<a className="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-gear-collapse" aria-expanded="false" href="#">Gear</a>
									<div className="collapse" id="shop-gear-collapse">
										<ul className="btn-toggle-nav list-unstyled fw-normal small">
											<li><a href="#" className="text-decoration-none ms-2">All Gear</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Building</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Explosive</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Melee</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Musical</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Navigation</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Power Up</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Ranged</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Social</a></li>
											<li><a href="#" className="text-decoration-none ms-2">Transport</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div className="col-md-10 d-flex flex-column">
						<div className="card p-3">
							<div className="graphictoria-shop-overlay">
								<Loader />
							</div>
							<div>
								<a className="graphictoria-item-card" href="#">
									<span className="card m-2">
										<img className="img-fluid" src="https://gtoria.local/images/testing/hat.png" />
										<div className="p-2">
											<p>Test hat</p>
											<p className="text-muted">Free</p>
										</div>
									</span>
								</a>
							</div>
						</div>
						<ul className="list-inline mx-auto mt-3">
							<li className="list-inline-item">
								<button className="btn btn-secondary" disabled><i className="fa-solid fa-angle-left"></i></button>
							</li>
							<li className="list-inline-item graphictoria-paginator">
								<span>Page&nbsp;</span>
								<input type="text" value="1" className="form-control" disabled />
								<span>&nbsp;of 20</span>
							</li>
							<li className="list-inline-item">
								<button className="btn btn-secondary" disabled><i className="fa-solid fa-angle-right"></i></button>
							</li>
						</ul>
					</div>
				</div>
			</div>
		);
	}
}

export default Shop;