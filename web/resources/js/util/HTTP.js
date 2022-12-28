/*
	Copyright © XlXi 2022
*/

const urlObject = new URL(document.location.href);

export function getCurrentDomain() {
	let url = process.env.MIX_APP_URL;
	url = url.replace(/https?:\/\//i, '');
	
	return url;
};

export function getProtocol() {
	return urlObject.protocol;
};

export function buildGenericApiUrl(subdomain, path) {
	return `${getProtocol()}//${subdomain}.${getCurrentDomain()}/${path}`;
};