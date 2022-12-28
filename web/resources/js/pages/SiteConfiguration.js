/*
	Copyright © XlXi 2022
*/

import $ from 'jquery';

const configId = 'vb-config-values';

$(document).ready(function() {
	if (document.getElementById(configId)) {
		let configItems = document.getElementById(configId).children;
		let items = [];
		
		for(let i = 0; i < configItems.length; i++) {
			let item = configItems[i];
			items.push({
				id: item.getAttribute('data-id'),
				name: item.getAttribute('data-name'),
				isMasked: (item.getAttribute('data-is-masked') === '1'),
				value: item.getAttribute('data-value'),
				created: item.getAttribute('data-created'),
				updated: item.getAttribute('data-updated')
			});
		}
		
		console.log(items);
	}
});