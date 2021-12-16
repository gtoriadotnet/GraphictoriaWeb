import axios from 'axios';
import React from 'react';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import { PageTransition } from '@steveeeie/react-page-transition';

import Config from '../config.js';

import Navbar from '../components/Navbar.js';
import Banner from '../components/Banner.js';
import Footer from '../components/Footer.js';

import Loader from '../Components/Loader.js';

import { Home } from '../Pages/Home.js';

import { Auth } from '../Pages/Auth.js';

import { NotFound, InternalServerError } from '../Pages/Errors.js';
import { Maintenance } from '../Pages/Maintenance.js';

import { Games } from '../Pages/Games.js';

import { About } from '../Pages/Legal/About.js';
import { Copyright } from '../Pages/Legal/Copyright.js';
import { Privacy } from '../Pages/Legal/Privacy.js';
import { Terms } from '../Pages/Legal/Terms.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

class App extends React.Component {
	constructor(props) {
		super(props);
		this.state = {maintenance: false, theme: 0, banners: [], offlineFetched: false};
	}
	
	componentDidMount() {
		var app = this; /* required for local functions since they can't access "this" */
		
		function updateBanners()
		{
			axios.get(protocol + 'apis.' + url + '/banners/data')
				.then((response) => {
					var result = [];
					response.data.map(function(banner){
						result.push(<Banner type={banner.type} description={banner.text} dismissible={banner.dismissable} />);
					});
					app.setState({banners: result});
				});
		}
		
		function updateOfflineStatus()
		{
			axios.get(protocol + 'apis.' + url + '/')
				.then((response) => {
					app.setState({maintenance: false});
				})
				.catch((error) => {
					if (error.response)
					{
						if(error.response.status == 503)
							app.setState({maintenance: true, theme: 1});
					}
				})
				.finally(() => {
					app.setState({offlineFetched: true});
				});
		}
		
		updateBanners();
		updateOfflineStatus();
		setInterval(updateBanners, 2*60*1000 /* 2 mins */);
		setInterval(updateOfflineStatus, 10*60*1000 /* 10 mins */);
	}
	
	render() {
		document.documentElement.classList.add(this.state.theme === 0 ? 'gtoria-light' : 'gtoria-dark');
		document.documentElement.classList.remove(!(this.state.theme === 0) ? 'gtoria-light' : 'gtoria-dark');
		
		return (
			this.state.offlineFetched == true ?
			<Router>
				<Navbar maintenanceEnabled={this.state.maintenance} />
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
										
										<Route exact path="/games">
											<Games/>
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
			:
			<div className="gtoria-loader-center">
				<Loader />
			</div>
		);
	}
}

export default App