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

import { Modal } from 'react-bootstrap';

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

const Item = (props) => {

    var id = useParams().id;
    const [validity, setValidity] = useState({error: false, message: ``, inputs: []});
    const [state, setState] = useState({offline: false, loading: true});
    const [item, setPost] = useState({item: [], sellingPrices: {sellingPrices: [], meta: [], currentPage: 1}});
    const [show, setShow] = useState(false);
    const user = props.user;
    const history = useHistory();

    const fetchItem = async () => {
        await axios.get(`${protocol}apis.${url}/fetch/item/${id}?page=${item.sellingPrices.currentPage}`, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {history.push(`/catalog`);}
            const res = data.data;
            SetTitle(res.item.title);
            setPost({item: res.item, sellingPrices: {...item.sellingPrices, sellingPrices: res.sellingPrices.data, meta: res.sellingPrices}});
        }).catch(error=>{console.log(error);});
    }

    const removeSale = async (saleId) => {
        setState({...state, loading: true});
        const body = new FormData();
        await axios.post(`${protocol}apis.${url}/api/catalog/remove/sale/${saleId}`, body, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            setState({...state, loading: false});
            const res = data.data;
            if (res.badInputs.length >= 1) {
                setValidity({error: true, message:res.message, inputs: res.badInputs});
                setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
            }else{
                setPost({item: res.item, sellingPrices: {...item.sellingPrices, sellingPrices: res.sellingPrices.data, meta: res.sellingPrices}});
            }
        }).catch(error=>{console.log(error);});
    }

    const buyItem = async (decision, isSelling, sellingId) => {
        setState({...state, loading: true});
        const body = new FormData();
        body.append(`decision`, decision);
        if (isSelling) body.append('sellingId', sellingId);
        await axios.post(`${protocol}apis.${url}/api/catalog/buy/${id}?page=${item.sellingPrices.currentPage}`, body, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {history.push(`/catalog`);}
            setState({...state, loading: false});
            const res = data.data;
            if (res.badInputs.length >= 1) {
                setValidity({error: true, message:res.message, inputs: res.badInputs});
                setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
            }else{
                setPost({item: res.item, sellingPrices: {...item.sellingPrices, sellingPrices: res.sellingPrices.data, meta: res.sellingPrices}});
            }
        }).catch(error=>{console.log(error);});
    }

    const sellItem = async (form) => {
        setState({...state, loading: true});
        const body = form;
        await axios.post(`${protocol}apis.${url}/api/catalog/sell/${id}?page=${item.sellingPrices.currentPage}`, body, {headers: {"X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            if (!data.data) {history.push(`/catalog`);}
            setState({...state, loading: false});
            const res = data.data;
            if (res.badInputs.length >= 1) {
                setValidity({error: true, message:res.message, inputs: res.badInputs});
                setTimeout(()=>{setValidity({...validity, error: false, inputs: res.badInputs});}, 4000);
            }else{
                setPost({item: res.item, sellingPrices: {...item.sellingPrices, sellingPrices: res.sellingPrices.data, meta: res.sellingPrices}});
                setShow(false);
            }
        }).catch(error=>{console.log(error);});
    }

    const paginatePrices = async (decision) => {
        paginate(decision, item.sellingPrices.currentPage, item.sellingPrices.meta).then(res=>{
            switch(res){
                case "increase":
                    setPost({...item, sellingPrices: {...item.sellingPrices, currentPage: item.sellingPrices.currentPage+1}});
                    break;
                case "decrease":
                    setPost({...item, sellingPrices: {...item.sellingPrices, currentPage: item.sellingPrices.currentPage-1}});
                    break;
                default:
                    break;
            }
        }).catch(error=>console.log(error));
    }

    useEffect(async ()=>{
        setState({...state, loading: false});
    }, []);

    useEffect(async()=>{
        setState({...state, loading: true});
        await fetchItem();
        setState({...state, loading: false});
    }, [item.sellingPrices.currentPage]);
	
		return (
			state.loading
			?
			<Loader />
			:
			<div className={`flex w-100 column jcc alc`}>
                <Modal show={show} onHide={(e)=>{setShow(false);}}>
                    <Modal.Header closeButton>
                        <Modal.Title>Sell {item.item.title}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body className={`flex flex-column`}>
                        <form onSubmit={(e)=>{e.preventDefault();sellItem(new FormData(e.target));}}>
                            <h4>How much are you selling for?</h4>
                            <input placeholder={`Ex. $500`} name={`price`} type={`number`} className={`mb-15`}/><br/>
                            <button className={`btn btn-success w-fit-content`} type={`submit`}>Sell Item</button>
                        </form>
                    </Modal.Body>
                    <Modal.Footer>
                        <button className={`btn btn-danger w-fit-content`} onClick={(e)=>{setShow(false);}}>Cancel</button>
                    </Modal.Footer>
                </Modal>
                {validity.error?
                    <div className={`px-5 mb-10 w-60 justify-content-center align-items-center`}>
                        <div className={`error-dialog w-100`}>
                            <p className={`mb-0`}>{validity.message}</p>
                        </div>
                    </div> 
                : null}
                <Card>
                    <div className={`flex w-100 column`}>
                        <div className={`flex row fs12`}>
                            <div className={`row w-fit-content`}>
                                <p className={`w-fit-content`}>Date Created:</p>
                                <p className={`w-fit-content padding-none`}>{item.item.created_at}</p>
                            </div>
                        </div>
                        <hr/>
                        <div className={`flex row`}>
                            <div className={`col text-left flex flex-column`}>
                                <img src='/images/testing/hat.png' className='img-fluid graphic-thumb-big'/>
                                <p>Price: <strong>{item.item.current_price? `$${item.item.current_price}` : `N/A`}</strong></p>
                                <p>Description: <i>'{item.item.description}'</i></p>
                                {item.item.ownsItem? <strong><p>You own this item!</p></strong> : null}
                                {!item.item.isLimited? <button className={`btn btn-success w-fit-content`} onClick={(e)=>{buyItem(`nonLimited`, false, null);}}>Buy Item</button> : item.item.stock >= 1? <button className={`btn btn-success w-fit-content`} onClick={(e)=>{buyItem(`limited`, false, null);}}>Buy Limited</button> : !item.item.isSelling && item.item.ownsItem? <button className={`btn btn-danger w-fit-content`} onClick={(e)=>{show? setShow(false) : setShow(true)}}>Sell Item</button> : item.item.isSelling? <p>You're selling this item!</p> : <p>Sorry, you don't own this item!</p>}
                            </div>
                            <div className={`flex column col flex-column jcc alc`}>
                                <div className={`flex flex-column`}>
                                    <h5 className={`text-left`}>Creator Info:</h5>
                                    <img src='/images/testing/avatar.png' className='img-fluid graphic-thumb-big' />
                                    <Link to={`/user/${item.item.creator.id}`}>{item.item.creator.username}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>
                {item.item.isLimited != 0 && item.item.stock <= 0? <div className={`container`}><hr className={`graphictoria-small-card mt-15 mb-15`}/></div> : null}
                {item.sellingPrices.sellingPrices.length <= 0 && item.item.isLimited != 0 && item.item.stock <= 0? <p className={`w-100 text-center`}>No one is selling this item yet!</p> : null}
                {item.item.isLimited != 0 && item.item.stock <= 0?
                <div className={`flex column w-100`}>
                {item.sellingPrices.sellingPrices.map(reply=>(
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
                                    <img src='/images/testing/headshot.png' className='img-fluid graphic-thumb' />
                                    <Link to={`/user/${reply.seller_id}`}>{reply.seller_name}</Link>
                                </div>
                                <div className={`col text-left float-right flex alc jcc`}>
                                    <div className={`flex flex-column flex alc jcc`}>
                                        <p>Price: ${reply.price}</p>
                                        <p>Item UID: {reply.uid}</p>
                                        {!reply.isMeta? <button className={`btn btn-success w-fit-content`} onClick={(e)=>{buyItem(`selling`, true, reply.id);}}>Buy</button> : <button className={`btn btn-danger w-fit-content`} onClick={(e)=>{removeSale(reply.id);}}>Take off Sale</button>}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Card>
                    </div>
                ))}
                {item.sellingPrices.sellingPrices.length >= 10?
                <div className={`w-100 jcc alc row mt-15`}>
                    {item.sellingPrices.currentPage >= 2? <button className={`w-fit-content btn btn-primary mr-15`} onClick={(e)=>{paginatePrices(true);}}>Previous Page</button> : null}
                    {item.sellingPrices.currentPage < item.sellingPrices.meta.last_page? <button className={`w-fit-content btn btn-primary`} onClick={(e)=>{paginatePrices(false);}}>Next Page</button> : null}
                </div> : null}
            </div> : null}
            </div>
		);
}

export default Item;
