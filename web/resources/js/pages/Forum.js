// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useEffect, useState } from "react";
import { Link, useHistory, useParams } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';
import { Card, CardTitle } from '../Layouts/Card.js';
import { paginate } from '../helpers/utils.js';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Forum = (props) => {

    var id = useParams().id;
    const [state, setState] = useState({offline: false, loading: true});
    const [categories, setCategoires] = useState([]);
    const [category, setCategory] = useState([]);
    const [posts, setPosts] = useState({posts: [], currentPage: 1, meta: []});
    const user = props.user;

    if (!id) id = 1;

    const fetchCategories = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/categories`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            setCategoires(data.data.categories);
        }).catch(error=>{console.log(error);});
    }

    const fetchCategory = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/category/${id}?page=${posts.currentPage}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {window.location.href=`/forum`;return;}
            setCategory(data.data.data);
            setPosts({...posts, posts: data.data.posts.data, meta: data.data.posts});
        }).catch(error=>{console.log(error);});
    }

    const paginatePosts = async (decision) => {
        paginate(decision, posts.currentPage, posts.meta).then(res=>{
            switch(res){
                case "increase":
                    setPosts({...posts, currentPage: posts.currentPage+1});
                    break;
                case "decrease":
                    setPosts({...posts, currentPage: posts.currentPage-1});
                    break;
                default:
                    break;
            }
        }).catch(error=>console.log(error));
    }

    useEffect(async ()=>{
        SetTitle(`Forum`);
        await fetchCategory();
        await fetchCategories();
        setState({...state, loading: false});
    }, []);

    useEffect(async()=>{
        setState({...state, loading: true});
        await fetchCategory();
        setState({...state, loading: false});
    }, [posts.currentPage]);
	
		return (
			state.loading || categories.length <= 0
			?
			<Loader />
			:
			<div className={`flex jcc alc w-100 column`}>
                <div class="jumbo w-60">
                    <div class="container">
                        <h1>Welcome to {category.title}!</h1>
                        <p>{category.description}</p>
                        {user?
                        <div className={`flex row justify-content-center`}>
                            {category.staffOnly == 1 && !user.power ? null : <Link className={`btn btn-success w-fit-content`} to={`/forum/post`}>Create a post</Link>}    
                        </div>
                        : null}
                    </div>
                </div>
                <div className="graphictoria-nav-splitter"></div>
                <div className={`row w-60`}>
                    <div className={`col-3 justify-content-center`}>
                        <h4>Categories:</h4>
                        {categories.map(category=>(
                            <>
                            <Link to={`/forum/category/${category.id}`}>{category.title}</Link><br/>
                            </>
                        ))}
                    </div>
                    <div className={`col justify-content-center`}>
                        {posts.posts.length <= 0 ? <p>There are currently no posts!</p> : null}
                        {posts.posts.map(post=>(
                            <>
                            <Link to={`/forum/post/${post.id}`} className={`flex graphic-post`}>
                                <div className={`flex column mr-15 alc`}>
                                    [Avatar.]
                                </div>
                                <div className={`flex row m-0`}>
                                    <div className={`flex row`}><h5 className={`m-0 mr-15`}>{post.locked == 1? <i class="fa-solid fa-lock"></i> : null} {post.title}</h5></div>
                                    <div className={`row fs12`}>
                                        <p className={`w-fit-content`}>Posted by:</p>
                                        <Link to={`/user/${post.creator.id}`} className={`w-fit-content padding-none`}>{post.creator.username}</Link>
                                    </div>
                                </div>
                            </Link>
                            </>
                        ))}
                        {posts.posts.length >= 1?
                        <div className={`w-100 jcc alc row mt-15`}>
                            {posts.currentPage >= 2? <button className={`w-fit-content btn btn-primary mr-15`} onClick={(e)=>{paginatePosts(true);}}>Previous Page</button> : null}
                            {posts.currentPage < posts.meta.last_page? <button className={`w-fit-content btn btn-primary`} onClick={(e)=>{paginatePosts(false);}}>Next Page</button> : null}
                        </div> : null}
                    </div>
                </div>
            </div>
		);
}

export default Forum;
