/*
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Shop from '../components/Shop';

const shopId = 'vb-shop-main';

$(document).ready(function() {
	if (document.getElementById(shopId)) {
		render(<Shop />, document.getElementById(shopId));
	}
});