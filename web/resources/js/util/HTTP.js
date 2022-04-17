// © XlXi 2021
// Graphictoria 5

const urlObject = new URL(document.location.href);

export function getCurrentDomain() {
	return urlObject.hostname.replace(/^[^.]+\./g, '');
};

export function getProtocol() {
	return urlObject.protocol;
};

export function buildGenericApiUrl(subdomain, path) {
	return `${getProtocol()}//${subdomain}.${getCurrentDomain()}/${path}`;
};