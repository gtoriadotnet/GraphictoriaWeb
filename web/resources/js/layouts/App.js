import axios from 'axios';
import React from 'react';
import { useState, useEffect } from "react";
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import { PageTransition, __esModule } from '@steveeeie/react-page-transition';
import { GuardProvider, GuardedRoute } from 'react-router-guards'

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
import { getCookie, setCookie } from '../helpers/utils.js';
import Dashboard from '../pages/Dashboard.js';
import Forum from '../pages/Forum.js';
import Post from '../pages/Post.js';
import CreatePost from '../pages/CreatePost.js';
import CreateReply from '../pages/CreateReply.js';
import Settings from '../pages/Settings.js';
import User from '../pages/User.js';
import Catalog from '../pages/Catalog.js';

axios.defaults.withCredentials = true

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const App = () => {

	const [state, setState] = useState({maintenance: false, theme: 0, banners: [], offlineFetch: false, loading: true});
	const [user, setUser] = useState([]);
	const [userLoad, setUserLoad] = useState(true);

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
		const body = new FormData();
		body.append('token', encodeURIComponent(getCookie(`gtok`)));
		body.append('decision', `metaUser`);
		axios.post(`${protocol}apis.${url}/fetch/user`, body).then((res)=>{
			if (res.data.data == `expired`) {setCookie(`gtok`, null, null);window.location.replace(`/login`);}
			setUser(res.data.data);
			setUserLoad(false);
		});
		return new Promise(async (resolve, reject)=>{
			resolve("good");
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

	const authMiddleware = (to, from, next) => {
		if (to.meta.auth) {
			if (user) {next();}
			next.redirect(`/login`);
		}else if (to.meta.guest) {
			if (!user) {next();}
			next.redirect(`/home`);
		}else if (to.meta.staff) {
			if (user && user.power) {next();}
			next.redirect(`/`);
		}
	}

	useEffect(async ()=>{ 
		await fetchUser();
		updateBanners();
		updateOfflineStatus();
		setInterval(updateBanners, 2*60*1000 /* 2 mins */);
		setInterval(updateOfflineStatus, 10*60*1000 /* 10 mins */);
		setState({loading: true});
	}, []);

	document.documentElement.classList.add(state.theme == 0 ? 'gtoria-light' : 'gtoria-dark');
	document.documentElement.classList.remove(!(state.theme == 0) ? 'gtoria-light' : 'gtoria-dark');

		return (
			!state.loading && !userLoad?
			<Router>
				<GuardProvider guards={[authMiddleware]}>
				<Navbar maintenanceEnabled={state.maintenance} user={user} />
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

								<Route exact path="/user/:id">
									<User user={user}/>
								</Route>
								
								<GuardedRoute exact path="/" meta={{guest: true}}>
									<Home user={user}/>
								</GuardedRoute>

								<GuardedRoute exact path="/home" meta={{auth: true}}>
									<Dashboard user={user}/>
								</GuardedRoute>

								<Route exact path="/forum">
									<Forum user={user}/>
								</Route>

								<Route exact path="/catalog">
									<Catalog user={user}/>
								</Route>

								<Route exact path="/catalog/category/:id">
									<Catalog user={user}/>
								</Route>

								<GuardedRoute exact path="/forum/post" meta={{auth: true}}>
									<CreatePost user={user}/>
								</GuardedRoute>

								<GuardedRoute exact path={`/auth/settings`} meta={{auth: true}}>
									<Settings user={user}/>
								</GuardedRoute>

								<GuardedRoute exact path="/forum/reply/:id" meta={{auth: true}}>
									<CreateReply user={user}/>
								</GuardedRoute>

								<Route exact path="/forum/category/:id">
									<Forum user={user}/>
								</Route>

								<Route exact path="/forum/post/:id">
									<Post user={user}/>
								</Route>
								
								<GuardedRoute exact path="/login" meta={{guest: true}}>
									<Auth location={user? null : location.pathname}/>
								</GuardedRoute>
								<GuardedRoute exact path="/register" meta={{guest: true}}>
									<Auth location={user? null : location.pathname}/>
								</GuardedRoute>
								<GuardedRoute exact path="/passwordreset" meta={{guest: true}}>
									<Auth location={user? null : location.pathname}/>
								</GuardedRoute>
								
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
					</GuardProvider>
			</Router>
			:
			<div className="gtoria-loader-center">
				<Loader />
			</div>
		);

}



export default App