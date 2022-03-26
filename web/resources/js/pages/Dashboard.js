// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useEffect, useState } from "react";
import { Link, useHistory } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';
import { MiniCard, MiniCardTitle } from '../Components/Card.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Dashboard = (props) => {

    const [state, setState] = useState({loading: true});
    const [feedState, setFeedState] = useState({loading: true});
    const user = props.user;

    useEffect(()=>{
        SetTitle(`Dashboard`);
        setState({loading: false});
    }, []);
	
		return (
			state.loading
			?
			<Loader />
			:
			<div className='container-lg'>
				<div className='row'>
					{/* User image, blog, friends */}
					<div className='col-md-3'>
						<h4>Hello, {user.username}!</h4>
						<div className='card text-center'>
							<p className='pt-3 px-3'>"{ user.about }"</p>
							<img src='/images/testing/avatar.png' className='img-fluid gt-charimg' />
						</div>
						<MiniCard className='mt-3'>
							<MiniCardTitle>Blog</MiniCardTitle>
							
							<ul className="text-center list-unstyled mb-1">
								<li className="pb-2"><a href="#" className="text-decoration-none fw-normal"><i className="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
								<li className="pb-2"><a href="#" className="text-decoration-none fw-normal"><i className="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
								<li className="pb-2"><a href="#" className="text-decoration-none fw-normal"><i className="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
							</ul>
							<div className="text-left px-2">
								<a href="#" className="text-decoration-none fw-normal">More <i className="fa-solid fa-caret-right"></i></a>
							</div>
						</MiniCard>
						
						<MiniCard className='mt-3 d-none d-md-flex'>
							<MiniCardTitle>Friends</MiniCardTitle>
						</MiniCard>
					</div>
					
					{/* Feed */}
					<div className='col-md-7 mt-3 mt-md-0'>
						<h4>My Feed</h4>
						<div className='card mb-2'>
							<div className='input-group p-2'>
								<input type='text' className='form-control' placeholder='What are you up to?' area-label='What are you up to?' />
								<button className='btn btn-secondary' type='button'>Share</button>
							</div>
						</div>
						{
							feedState.loading
							?
							<div className='text-center'>
								<Loader />
							</div>
							:
							<div className='card'>
								
							</div>
						}
					</div>
					
					{/* Recently Played */}
					<div className='col-md-2 d-none d-md-block'>
						<h6>recently played</h6>
					</div>
				</div>
			</div>
		);
}

export default Dashboard;
