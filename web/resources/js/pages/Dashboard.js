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
import { paginate } from '../helpers/utils.js';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Dashboard = (props) => {

    const [state, setState] = useState({loading: true});
	const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
    const [feedState, setFeedState] = useState({loading: true, posts: {posts: [], meta: [], currentPage: 1}});
    const user = props.user;

	const createFeed = async () => {
		setFeedState({...feedState.posts, loading: true});
		await axios.post(`${protocol}apis.${url}/api/create/feed`, new FormData(document.getElementById(`form`)), {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
			const res = data.data;
			if (res.badInputs.length >= 1) {
                setValidity({error: true, message:res.message, inputs: res.badInputs});
				setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
				setFeedState({...feedState, loading: false});
            }else{
				document.getElementById('input').value = "";
                setFeedState({loading: false, posts: {...feedState.posts, posts: res.data.data, meta: res.data}});
            }
        }).catch(error=>{console.log(error);});
	}

	const fetchFeed = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/feed?page=${feedState.posts.currentPage}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
			const res = data.data;
			setFeedState({loading: false, posts: {...feedState.posts,posts: res.data.data, meta: res.data}});
        }).catch(error=>{console.log(error);});
    }

    const paginatePosts = async (decision) => {
        paginate(decision, feedState.posts.currentPage, feedState.posts.meta).then(res=>{
            switch(res){
                case "increase":
                    setFeedState({...feedState, posts: {...feedState.posts, currentPage: feedState.posts.currentPage+1}});
                    break;
                case "decrease":
                    setFeedState({...feedState, posts: {...feedState.posts, currentPage: feedState.posts.currentPage-1}});
                    break;
                default:
                    break;
            }
        }).catch(error=>console.log(error));
    }

    useEffect(()=>{
        SetTitle(`Dashboard`);
		fetchFeed();
        setState({loading: false});
    }, []);

	useEffect(async()=>{
        setState({loading: true});
        await fetchFeed();
        setState({loading: false});
    }, [feedState.posts.currentPage]);
	
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
						{validity.error?
                            <div className={`px-5 mb-10`}>
                                <div className={`error-dialog`}>
                                    <p className={`mb-0`}>{validity.message}</p>
                                </div>
                            </div> 
                        : null}
						<div className='card mb-2'>
							<form className='input-group p-2' onSubmit={(e)=>{e.preventDefault();createFeed();}} id={`form`}>
								<input type='text' className='form-control' placeholder='What are you up to?' area-label='What are you up to?' name={`body`} id={`input`}/>
								<button className='btn btn-secondary' type='submit'>Share</button>
							</form>
						</div>
						{
							feedState.loading
							?
							<div className='text-center'>
								<Loader />
							</div>
							:
							<>
							<div className='card flex-column pt-3 px-3 align-content-center'>
							{feedState.posts.posts.map(feed=>(
								<>
								<Link className={`flex flex-row align-items-center a`} to={`/user/${feed.user_id}`}>
									<div className={`flex flex-column justify-content-center text-center w-fit-content`}>
										<p className='mr-10'>{feed.creatorName}</p>
										<img src='/images/testing/headshot.png' className='img-fluid graphic-thumb' />
									</div>
									<div className={`flex align-items-center col`}>
										<p className='mr-10'><i>"{feed.body}"</i></p>
									</div>
								</Link>
								<hr/>
								</>
							))}
							</div>
							{feedState.posts.posts.length <= 0? <p>There isn't any posts right now!</p> : null}
							{feedState.posts.posts.length >= 1?
							<div className={`w-100 jcc alc row mt-15`}>
								{feedState.posts.currentPage >= 2? <button className={`w-fit-content btn btn-primary mr-15`} onClick={(e)=>{paginatePosts(true);}}>Previous Page</button> : null}
								{feedState.posts.currentPage < feedState.posts.meta.last_page? <button className={`w-fit-content btn btn-primary`} onClick={(e)=>{paginatePosts(false);}}>Next Page</button> : null}
							</div> : null}
							</>
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
