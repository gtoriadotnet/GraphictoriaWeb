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

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Post = (props) => {

    var id = useParams().id;
    const [state, setState] = useState({offline: false, loading: true});
    const [post, setPost] = useState({post: [], replies: {replies: [], meta: [], currentPage: 1}});
    const user = props.user;
    const history = useHistory();

    const fetchPost = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/post/${id}?page=${post.replies.currentPage}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {history.push(`/forum`);}
            const res = data.data;
            setPost({post: res.post, replies: {...post.replies, replies: res.replies.data, meta: res.replies}});
        }).catch(error=>{console.log(error);});
    }

    const paginateReplies = async (decision) => {
        paginate(decision, post.replies.currentPage, post.replies.meta).then(res=>{
            switch(res){
                case "increase":
                    setPost({...post, replies: {...post.replies, currentPage: post.replies.currentPage+1}});
                    break;
                case "decrease":
                    setPost({...post, replies: {...post.replies, currentPage: post.replies.currentPage-1}});
                    break;
                default:
                    break;
            }
        }).catch(error=>console.log(error));
    }

    useEffect(async ()=>{
        SetTitle(`Forum`);
        setState({...state, loading: false});
    }, []);

    useEffect(async()=>{
        setState({...state, loading: true});
        await fetchPost();
        setState({...state, loading: false});
    }, [post.replies.currentPage]);
	
		return (
			state.loading
			?
			<Loader />
			:
			<div className={`flex w-100 column jcc alc`}>
                {post.post.locked == 1? 
                <div className={`container`}>
                    <div className={`error-dialog graphictoria-small-card`}>
                        <p className={`mb-0`}>This post is locked!</p>
                    </div> 
                </div>
                : 
                <div className={`container`}>
                    <div className={`graphictoria-small-card row`}>
                        {user && user.username? <Link className="btn btn-primary px-5 w-fit-content mb-15" to={`/forum/reply/${post.post.id}`}>Reply</Link> : <p>Sign in to reply!</p>}
                    </div> 
                </div>}
                <Card>
                    <div className={`flex w-100 column`}>
                        <div className={`flex row fs12`}>
                            <div className={`row w-fit-content`}>
                                <p className={`w-fit-content`}>Post Title:</p>
                                <p className={`w-fit-content padding-none`}><i><strong>'{post.post.title}'</strong></i></p>
                            </div>
                            <div className={`row w-fit-content`}>
                                <p className={`w-fit-content`}>Date posted:</p>
                                <p className={`w-fit-content padding-none`}>{post.post.created_at}</p>
                            </div>
                        </div>
                        <hr/>
                        <div className={`flex row`}>
                            <div className={`flex column jcc alc col-3`}>
                                <p className={`mb-10`}>[Avatar.]</p>
                                <Link to={`/user/${post.post.creator.id}`}>{post.post.creator.username}</Link>
                                
                            </div>
                            <div className={`col text-left`}>
                                <p className={`m-0`}>{post.post.body}</p>
                            </div>
                        </div>
                    </div>
                </Card>
                <div className={`container`}><hr className={`graphictoria-small-card mt-15 mb-15`}/></div>
                {post.replies.replies.length <= 0 && post.post.locked != 1? <p className={`w-100 text-center`}>There isn't any replies to this post yet!</p> : null}
                <div className={`flex column w-100`}>
                    {post.replies.replies.map(reply=>(
                        <div className={`mb-15`}>
                            <Card>
                            <div className={`flex w-100 column`}>
                                <div className={`flex row fs12`}>
                                    <div className={`row w-fit-content`}>
                                        <p className={`w-fit-content`}>Date posted:</p>
                                        <p className={`w-fit-content padding-none`}>{reply.created_at}</p>
                                    </div>
                                </div>
                                <hr/>
                                <div className={`flex row`}>
                                    <div className={`flex column jcc alc col-3`}>
                                        <p className={`mb-10`}>[Avatar.]</p>
                                        <Link to={`/user/${reply.creator_id}`}>{reply.creator_name}</Link>
                                    </div>
                                    <div className={`col text-left`}>
                                        <p className={`m-0`}>{reply.body}</p>
                                    </div>
                                </div>
                            </div>
                        </Card>
                        </div>
                    ))}
                    {post.replies.replies.length >= 10?
                    <div className={`w-100 jcc alc row mt-15`}>
                        {post.replies.currentPage >= 2? <button className={`w-fit-content btn btn-primary mr-15`} onClick={(e)=>{paginateReplies(true);}}>Previous Page</button> : null}
                        {post.replies.currentPage < post.replies.meta.last_page? <button className={`w-fit-content btn btn-primary`} onClick={(e)=>{paginateReplies(false);}}>Next Page</button> : null}
                    </div> : null}
                </div>
            </div>
		);
}

export default Post;
