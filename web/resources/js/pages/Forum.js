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

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Forum = (props) => {

    var id = useParams().id;
    const [state, setState] = useState({offline: false, loading: true});
    const [categories, setCategoires] = useState([]);
    const [category, setCategory] = useState([]);
    const [posts, setPosts] = useState({posts: [], currentPage: 0, meta: []});
    const user = props.user;

    if (!id) id = 1;

    const fetchCategories = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/categories`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            setCategoires(data.data.data);
        }).catch(error=>{console.log(error);});
    }

    const fetchCategory = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/category/${id}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {window.location.href=`/forum`;return;}
            setCategory(data.data.data);
            setPosts({...posts, posts: data.data.posts.data, meta: data.data.posts});
        }).catch(error=>{console.log(error);});
    }

    useEffect(async ()=>{
        SetTitle(`Forum`);
        await fetchCategories();
        await fetchCategory();
        setState({...state, loading: false});
    }, []);
	
		return (
			state.loading
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
                            <Link className={`btn btn-success w-20`} to={`/forum/post`}>Create a post</Link>    
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
                                    <h5 className={`m-0`}>{post.title}</h5>
                                    <div className={`row fs12`}>
                                        <p>Posted by:</p>
                                        <Link to={`/user/${post.creator.id}`}>{post.creator.username}</Link>
                                    </div>
                                </div>
                            </Link>
                            </>
                        ))}
                    </div>
                </div>
            </div>
		);
}

export default Forum;
