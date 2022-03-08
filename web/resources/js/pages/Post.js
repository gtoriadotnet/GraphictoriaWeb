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

const Post = (props) => {

    var id = useParams().id;
    const [state, setState] = useState({offline: false, loading: true});
    const [post, setPost] = useState({post: [], replies: {replies: [], meta: [], currentPage: 0}});
    const user = props.user;

    const fetchPost = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/post/${id}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {window.location.href=`/forum`;return;}
            setPost({post: data.data.post, replies: {replies: data.data.replies.data, meta: data.data.replies, currentPage: 0}});
        }).catch(error=>{console.log(error);});
    }

    useEffect(async ()=>{
        SetTitle(`Forum`);
        await fetchPost();
        setState({...state, loading: false});
    }, []);
	
		return (
			state.loading
			?
			<Loader />
			:
			<div className={`flex jcc alc w-100 column`}>
                <div className={`graphic-post w-40`}>
                    {/* Time&Date goes here. */}
                    <div className={`flex w-100`}>
                        <div className={`flex column mr-15`}>
                            <p>Posted by:</p>
                            <Link to={`/user/${post.post.creator.id}`}>{post.post.creator.username}</Link>
                        </div>
                        <div className={`flex row`}>
                            <p className={`m-0`}>{post.post.body}</p>
                        </div>
                    </div>
                </div>
            </div>
		);
}

export default Post;
