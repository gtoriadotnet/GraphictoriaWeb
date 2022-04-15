// © XlXi 2021
// Graphictoria 5

import axios from "axios";
import { useHistory } from "react-router-dom";
import Config from '../config.js';

axios.defaults.withCredentials = true

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

export function CreateAccount(form)
{

    const body = form;
    body.append('host', url);
    var badInputs = [];

    return new Promise(async (resolve, reject)=>{
        await axios.post(`${protocol}apis.${url}/v1/user/register`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest", "accept":"application/json"}}).then(data=>{
            const res = data.data;
            if (res.badInputs.length >= 1) {
                badInputs=res.badInputs;
                resolve({message: res.message, inputs: res.badInputs});
                return;
            }else{
                resolve("good")
            }
        }).catch(error=>{console.log(error);});

    });
}

export function LoginToAccount(form) {

    const body = form;
    body.append('host', url);
    var badInputs = [];
    
    return new Promise(async (resolve, reject)=>{
        await axios.post(`${protocol}apis.${url}/v1/user/login`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            const res = data.data;
            if (res.badInputs.length >= 1) {
                badInputs=res.badInputs;
                resolve({message: res.message, inputs: res.badInputs});
                return;
            }else{
                resolve("good")
            }
        }).catch(error=>{console.log(error);});

    });

}

export function CreateForum(form) {

    const body = form;
    var badInputs = [];

    return new Promise(async (resolve, reject)=>{
        axios.post(`${protocol}apis.${url}/api/create/forum`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            resolve("good");
        }).catch(error=>{console.log(error);});
    });
}

export function Logout() {
    const body = form;
    var badInputs = [];

    axios.post(`${protocol}apis.${url}/v1/user/logout`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
        window.location.replace(`/`);
        resolve("good");
    }).catch(error=>{console.log(error);});

}
