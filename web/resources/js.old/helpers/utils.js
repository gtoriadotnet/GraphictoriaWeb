// © XlXi 2021
// Graphictoria 5

import { useEffect } from 'react';

export var user;

/* Cookie functions stolen from https://www.w3schools.com/js/js_cookies.asp | couldn't be asked tbh. */

export function setCookie(cname, cvalue, exdays) {
	const d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	let expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

export function getCookie(cname) {
	let name = cname + "=";
	let ca = document.cookie.split(';');
	for(let i = 0; i < ca.length; i++) {
	let c = ca[i];
	while (c.charAt(0) == ' ') {
		c = c.substring(1);
	}
	if (c.indexOf(name) == 0) {
		return c.substring(name.length, c.length);
	}
	}
	return "";
}

export function checkCookie() {
	let user = getCookie("username");
	if (user != "") {
	alert("Welcome again " + user);
	} else {
	user = prompt("Please enter your name:", "");
	if (user != "" && user != null) {
		setCookie("username", user, 365);
	}
	}
} 

export const paginate = async (decision, currentPage, meta) => {
	if (decision && currentPage <= 1) return;
	if (!decision && currentPage >= meta.last_page) return;
	switch (decision) {
		case true:
			return new Promise(async (resolve, reject)=>{
				resolve("decrease");
			});
			break;
		case false:
			return new Promise(async (resolve, reject)=>{
				resolve("increase");
			});
			break;
		default:
			break;
	}
}

export function useOnClickOutside(refs, handler) {
	useEffect(
		() => {
			const listener = (event) => {
				var shouldReturn = false;
				
				refs.map(
					function(val)
					{
						if (shouldReturn == false && (!val.current || val.current.contains(event.target))) 
						{
							shouldReturn = true;
						}
					}
				);
				
				if(shouldReturn)
				{
					return;
				}
				
				handler(event);
			};
			document.addEventListener('mousedown', listener);
			document.addEventListener('touchstart', listener);
			return () => {
				document.removeEventListener('mousedown', listener);
				document.removeEventListener('touchstart', listener);
			};
		},
		[refs, handler]
	);
}
