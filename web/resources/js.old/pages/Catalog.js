// © XlXi 2021
// Graphictoria 5

import axios from 'axios';
import React, { useEffect, useState } from "react";
import { Link, useHistory, useParams } from "react-router-dom";

import Config from '../config.js';

import SetTitle from "../Helpers/Title.js";

import Loader from '../Components/Loader.js';

import { GenericErrorModal } from './Errors.js';
import { Card, CardTitle } from '../Components/Card.js';
import { paginate } from '../helpers/utils.js';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Catalog = (props) => {

    var id = useParams().id;
    const [state, setState] = useState({loading: true});
    const [categories, setCategoires] = useState([]);
    const [category, setCategory] = useState([]);
    const [items, setItems] = useState({items: [], currentPage: 1, meta: []});
    const user = props.user;

    if (!id) id = 1;

    const fetchCategories = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/categories/catalog`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            setCategoires(data.data.categories);
        }).catch(error=>{console.log(error);});
    }

    const fetchCategory = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/category/catalog/${id}?page=${items.currentPage}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {window.location.href=`/forum`;return;}
            setCategory(data.data.data);
            setItems({...items, items: data.data.items.data, meta: data.data.items});
        }).catch(error=>{console.log(error);});
    }

    const paginateitems = async (decision) => {
        paginate(decision, items.currentPage, items.meta).then(res=>{
            switch(res){
                case "increase":
                    setItems({...items, currentPage: items.currentPage+1});
                    break;
                case "decrease":
                    setItems({...items, currentPage: items.currentPage-1});
                    break;
                default:
                    break;
            }
        }).catch(error=>console.log(error));
    }

    useEffect(async ()=>{
        SetTitle(`Catalog`);
        await fetchCategory();
        await fetchCategories();
        setState({...state, loading: false});
    }, []);

    useEffect(async()=>{
        setState({...state, loading: true});
        await fetchCategory();
        setState({...state, loading: false});
    }, [items.currentPage]);
	
		return (
			state.loading || categories.length <= 0
			?
			<Loader />
			:
			<div className={`flex jcc alc w-100 column`}>
                <div className="graphictoria-nav-splitter"></div>
                <div className={`row w-60`}>
                    <div className={`col-3 justify-content-center flex-column`}>
                        <div>
                            <h4>Categories:</h4>
                            {categories.map(category=>(
                                <>
                                <Link to={`/catalog/category/${category.id}`}>{category.title}</Link><br/>
                                </>
                            ))}
                        </div>
                        <div className="graphictoria-nav-splitter"></div>
                        <div>
                            <h4>Options:</h4>
                            <div className={`flex flex-column`}>
                                <h6>Search for an item:</h6>
                                <input placeholder={`temp`}/>
                            </div>
                        </div>
                    </div>
                    <div className={`col justify-content-center`}>
                        {items.items.length <= 0 ? <p>There are currently no items!</p> : null}
                        <div className={`flex flex-row flex-wrap`}>
                            {items.items.map(item=>(
                                <>
                                <Link to={`/item/${item.id}`} className={`flex graphic-post-column col-3`}>
                                    <div className={`flex column mb-10 alc`}>
                                        <img src='/images/testing/hat.png' className='img-fluid graphic-thumb' />
                                    </div>
                                    <div className={`flex flex-column m-0 jcc alc`}>
                                        <div className={`flex row w-fit-content`}><h6 className={`m-0 mr-15 fs13`}>{item.title}</h6></div>
                                        <div className={`row fs15 w-fit-content`}>
                                            <p className={`w-fit-content`}>{item.current_price? `$${item.current_price}` : `N/A`}</p>
                                        </div>
                                    </div>
                                </Link>
                                </>
                            ))}
                        </div>
                        {items.items.length >= 1?
                        <div className={`w-100 jcc alc row mt-15`}>
                            {items.currentPage >= 2? <button className={`w-fit-content btn btn-primary mr-15`} onClick={(e)=>{paginateitems(true);}}>Previous Page</button> : null}
                            {items.currentPage < items.meta.last_page? <button className={`w-fit-content btn btn-primary`} onClick={(e)=>{paginateitems(false);}}>Next Page</button> : null}
                        </div> : null}
                    </div>
                </div>
            </div>
		);
}

export default Catalog;
