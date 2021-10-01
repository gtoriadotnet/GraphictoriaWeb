import axios from 'axios';
import React, { useState } from 'react';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import { PageTransition } from '@steveeeie/react-page-transition';

import Config from '../config.js';

import Navbar from '../components/Navbar.js';
import Banner from '../components/Banner.js';
import Footer from '../components/Footer.js';

import { Home } from '../Pages/Home.js';

import { Auth } from '../Pages/Auth.js';

import { NotFound, InternalServerError } from '../Pages/Errors.js';
import { Maintenance } from '../Pages/Maintenance.js';

import { About } from '../Pages/Legal/About.js';
import { Copyright } from '../Pages/Legal/Copyright.js';
import { Privacy } from '../Pages/Legal/Privacy.js';
import { Terms } from '../Pages/Legal/Terms.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

class App extends React.Component {
	constructor(props) {
		super(props);
		this.state = {maintenance: false, theme: 0, banners: []};
	}
	
	componentDidMount() {
		var app = this; /* required for local functions since they can't access "this" */
		
		function updateBanners()
		{
			axios.get(protocol + 'api.' + url + '/web/activebanners').then((response) => {
				var result = [];
				response.data.map(function(banner){
					result.push(<Banner type={banner.type} description={banner.text} dismissible={banner.dismissable} />);
				});
				app.setState({banners: result});
			});
		}
		
		if(this.state.maintenance === true)
		{
			this.setState({theme: 1});
		}
		
		updateBanners();
		setInterval(updateBanners, 2*60*1000 /* 2 mins */);
	}
	
	render() {
		document.documentElement.classList.add(this.state.theme === 0 ? 'gtoria-light' : 'gtoria-dark');
		document.documentElement.classList.remove(!(this.state.theme === 0) ? 'gtoria-light' : 'gtoria-dark');
		
		return (
			<Router>
				<Navbar maintenanceEnabled={false} />
				{
					
					this.state.banners.length !== 0 ?
					this.state.banners
					:
					null
					
				}
				
				<Route render={({ location }) => {
						return (
						<PageTransition preset="slide" enterAnimation="scaleUp" exitAnimation="scaleDown" transitionKey={location.pathname}>
							<div className="graphictoria-nav-splitter">
							</div>
							{
								!this.state.isError
								?
									<Switch location={location}>
										<Route exact path="/legal/about-us">
											<About/>
										</Route>
										<Route exact path="/legal/dmca">
											<Copyright/>
										</Route>
										<Route exact path="/legal/privacy-policy">
											<Privacy/>
										</Route>
										<Route exact path="/legal/terms-of-service">
											<Terms/>
										</Route>
										
										{
											this.state.maintenance ?
											<Route path="*">
												<Maintenance/>
											</Route>
											:
											null
										}
										
										<Route exact path="/">
											<Home/>
										</Route>
										
										<Route exact path="/login">
											<Auth location={location.pathname}/>
										</Route>
										<Route exact path="/register">
											<Auth location={location.pathname}/>
										</Route>
										<Route exact path="/passwordreset">
											<Auth location={location.pathname}/>
										</Route>
										
										<Route path="*">
											<NotFound/>
										</Route>
									</Switch>
								:
									<InternalServerError stackTrace={this.state.stack}/>
							}
							<div className="graphictoria-nav-splitter">
							</div>
							<Footer/>
						</PageTransition>
						);
					}}/>
			</Router>
		);
	}
}

export default App