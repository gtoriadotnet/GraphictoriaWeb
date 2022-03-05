// © XlXi 2021
// Graphictoria 5

import axios from "axios";
import Config from '../config.js';

axios.defaults.withCredentials = true

var url = Config.BaseUrl.replace('http://', '');
var protocol = Config.Protocol;

export async function CreateAccount(form)
{
    console.log(form.get('username'));

    const finished = false;
    const body = form;

    await axios.post(`${protocol}apis.${url}/account/register`, body, {headers: {"Access-Control-Allow-Origin": "*"}}).then(res=>{
        console.log(res);
    }).catch(error=>console.log(error));

    return new Promise((resolve, reject)=>{

        if (finished) {
            resolve("good");
        }else{
            resolve({message: `bad`, inputs: [`username`]});
        }
    });
}

export const LoginToAccount = async (form) => {

    console.log(form.get('Username'));

    const finished = true;
    const body = form;

    await axios.post(`${protocol}${url}/api/login`, body).then(res=>{
        console.log(body);
    }).catch(error=>console.log(error));

    return new Promise((resolve, reject)=>{

        if (finished) {
            resolve("good");
        }else{
            reject({message: `bad`, inputs: [`username`]});
        }
    });

}
