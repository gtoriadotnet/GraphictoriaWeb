import axios from 'axios';
import React from 'react';
import { useState, useEffect } from "react";
import ReactDOM from "react-dom";
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

axios.defaults.withCredentials = true

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const App = () => {

	const [state, setState] = useState({maintenance: false, theme: 0, banners: [], offlineFetch: false, user: []});
	var finished = false;

	function updateBanners()
		{
			axios.get(`${protocol}apis.${url}/banners/data`)
				.then((response) => {
					var result = [];
					response.data.map(function(banner){
						result.push(<Banner type={banner.type} description={banner.text} dismissible={banner.dismissable} />);
					});
					setState({banners: result});
				});
		}

		function fetchUser() {
			axios.post(`${protocol}apis.${url}/fetch/user`).then((res)=>{
				setState({user: res.data.data}, (e)=>{console.log(state.user)});
			});
		}
		
		function updateOfflineStatus()
		{
			axios.get(`${protocol}apis.${url}/`)
				.then((response) => {
					if(state.maintenance == true)
						window.location.reload();
				})
				.catch((error) => {
					if (error.response)
					{
						if(error.response.status == 503)
							setState({maintenance: true, theme: 1});
					}
				})
				.finally(() => {
					setState({offlineFetched: true});
				});
		}

	useEffect(async ()=>{ 
		await fetchUser();
		updateBanners();
		updateOfflineStatus();
		setInterval(updateBanners, 2*60*1000 /* 2 mins */);
		setInterval(updateOfflineStatus, 10*60*1000 /* 10 mins */);
	}, []);

	document.documentElement.classList.add(state.theme == 0 ? 'gtoria-light' : 'gtoria-dark');
	document.documentElement.classList.remove(!(state.theme == 0) ? 'gtoria-light' : 'gtoria-dark');

		return (
			state.offlineFetched == true ?
			<Router>
				<Navbar maintenanceEnabled={state.maintenance} />
				{state.banners && state.banners.length >= 1 ? state.banners : null}
				
				<Route render={({ location }) => {
						return (
						<PageTransition preset="slide" enterAnimation="scaleUp" exitAnimation="scaleDown" transitionKey={location.pathname}>
							<div className="graphictoria-nav-splitter">
							</div>
							{
								!state.isError
								?
								<Switch location={location}>
								<Route exact path="/legal/about-us" component={About}/> {/* <- Simpler way of writing this */}
								<Route exact path="/legal/dmca" component={Copyright}/>
								<Route exact path="/legal/privacy-policy" component={Privacy}/>
								<Route exact path="/legal/terms-of-service" component={Terms}/>
								{state.maintenance ? <Route path="*" component={Maintenance}/> : null}
								
								<Route exact path="/" component={Home}/>
								
								<Route exact path="/login">
									{state.user? <NotFound/> : <Auth location={location.pathname}/>}
								</Route>
								<Route exact path="/register">
									{state.user? <NotFound/> : <Auth location={location.pathname}/>}
								</Route>
								<Route exact path="/passwordreset">
									{state.user? <NotFound/> : <Auth location={location.pathname}/>}
								</Route>
								
								<Route exact path="/games" component={Games}/>
								
								<Route path="*" component={NotFound}/>
							</Switch>
								:
									<InternalServerError stackTrace={state.stack}/>
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



export default App