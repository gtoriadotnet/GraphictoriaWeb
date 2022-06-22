// © XlXi 2021
// Graphictoria 5

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Shop from '../components/Shop';

const shopId = 'gt-shop-main';

$(document).ready(function() {
	if (document.getElementById(shopId)) {
		render(<Shop />, document.getElementById(shopId));
	}
});