// © XlXi 2021
// Graphictoria 5

import axios from "axios";
import Config from '../config.js';

axios.defaults.withCredentials = true

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

export function CreateAccount(form)
{

    const body = form;
    var badInputs = [];

    return new Promise(async (resolve, reject)=>{
        await axios.post(`${protocol}apis.${url}/account/register`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            const res = data.data;
            if (res.badInputs.length >= 1) {
                badInputs=res.badInputs;
                resolve({message: res.message, inputs: res.badInputs});
            }
            resolve("good");
        }).catch(error=>{console.log(error);});

    });
}

export function LoginToAccount(form) {

    const body = form;
    var badInputs = [];
    
    return new Promise(async (resolve, reject)=>{

        await axios.post(`${protocol}apis.${url}/account/login`, body, {headers: {'X-CSRF-TOKEN': document.querySelector(`meta[name="csrf-token"]`).content, "X-Requested-With":"XMLHttpRequest"}}).then(data=>{
            const res = data.data;
            if (res.badInputs.length >= 1) {
                badInputs=res.badInputs;
                resolve({message: res.message, inputs: res.badInputs});
            }
            resolve("good");
        }).catch(error=>{console.log(error);});

    });

}
