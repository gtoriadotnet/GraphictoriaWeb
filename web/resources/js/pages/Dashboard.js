// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useEffect, useState } from "react";
import { Link, useHistory } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';
import { Card, CardTitle } from '../Layouts/Card.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Dashboard = (props) => {

    const [state, setState] = useState({offline: false, loading: true});
    const user = props.user;

    useEffect(()=>{
        SetTitle(`Dashboard`);
        setState({...state, loading: false});
    }, []);
	
		return (
			state.loading
			?
			<Loader />
			:
			<div className='container'>
				<h4>Hello, {user.username}!</h4>
				<div className='row'>
					{/* User image, blog, friends */}
					<div className='col-3'>
						<div className='card text-center'>
							<p className='pt-3 px-3'>"{ user.about }"</p>
							<img src='/images/testing/avatar.png' className='img-fluid' />
						</div>
						<div className='card mt-3'>
							<div className='card-body p-2 text-center'>
								<h6 className='card-title'>Blog</h6>
								<hr className='mx-5 my-0 mb-2' />
								<ul className="text-center list-unstyled mb-1">
									<li className="pb-2"><a href="#" className="text-decoration-none fw-normal"><i className="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
									<li className="pb-2"><a href="#" className="text-decoration-none fw-normal"><i className="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
									<li className="pb-2"><a href="#" className="text-decoration-none fw-normal"><i className="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
								</ul>
								<div className="text-left px-2">
									<a href="#" className="text-decoration-none fw-normal">More <i className="fa-solid fa-caret-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					
					{/* Feed */}
					<div className='col-7'>
					
					</div>
					
					{/* Recently Played */}
					<div className='col-2'>
					
					</div>
				</div>
			</div>
		);
}

export default Dashboard;
