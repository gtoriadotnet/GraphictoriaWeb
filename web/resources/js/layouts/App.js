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

/* Tell me if u don't like the way this is structured, because both ways work fine. Its just that this way is easier (for me personally) & more updated. */

const App = () => {

	const [state, setState] = useState({maintenance: false, theme: 0, banners: [], offlineFetch: false}); /* Easier way of defining constants */
	/* Defining a new constant is just like above -> [custom, setCustom] = useState({something: false}); OR useState(false) */

	function updateBanners()
		{
			axios.get(`${protocol}apis.${url}/banners/data`) /* `` <-- Using these characters instead allow you to insert variables inside the string via ${}, instead of adding '++' */
				.then((response) => {
					var result = [];
					response.data.map(function(banner){
						result.push(<Banner type={banner.type} description={banner.text} dismissible={banner.dismissable} />);
					});
					setState({banners: result}); /* Using the useState function, you can define a custom function name for changing that constant. Use that custom name whenever you want to change that constant. I just called this one 'setState'. */
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

	useEffect(()=>{ /* useEffect = componentDidMount btw */
		/* Don't need 'var app' anymore. Functions don't need to be inside of useEffect. */
		
		updateBanners();
		updateOfflineStatus();
		setInterval(updateBanners, 2*60*1000 /* 2 mins */);
		setInterval(updateOfflineStatus, 10*60*1000 /* 10 mins */);
		console.log(state);
	}, []); /* Adding ", []" allows the useEffect function to run only once. 
	The cool thing about this is that if you put a constant name inside of the brackets, ex. [state], then the useEffect function will run everytime that constant is updated. 
	*/

	document.documentElement.classList.add(state.theme == 0 ? 'gtoria-light' : 'gtoria-dark');
	document.documentElement.classList.remove(!(state.theme == 0) ? 'gtoria-light' : 'gtoria-dark');
		/* No need for the Render() function anymore. */
		return (
			state.offlineFetched == true ?
			<Router>
				<Navbar maintenanceEnabled={state.maintenance} />
				{
					
					state.banners && state.banners.length >= 1 ? /* Instead of just calling the length of the array, you might want to also check if it exists at all yet. */
					state.banners /* Instead of calling 'this', since constants are global, you can just call whatever you named the constant. */
					:
					null
					
				}
				
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
								{state.maintenance ? <Route path="*" component={Maintenance}/> : null} {/* Just kind of minimized it */}
								
								<Route exact path="/" component={Home}/>
								
								<Route exact path="/login">
									<Auth location={location.pathname}/>
								</Route>
								<Route exact path="/register">
									<Auth location={location.pathname}/>
								</Route>
								<Route exact path="/passwordreset">
									<Auth location={location.pathname}/>
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