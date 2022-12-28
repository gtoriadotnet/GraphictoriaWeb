/*
	Copyright © XlXi 2022
*/

import $ from 'jquery';
import * as Bootstrap from 'bootstrap';
import axios from 'axios';

import React from 'react';
import { render } from 'react-dom';

import { buildGenericApiUrl } from '../util/HTTP.js';
import SearchBar from './SearchBar';

axios.defaults.withCredentials = true;

const searchBarId = 'virtubrick-nav-searchbar';

$(document).ready(function() {
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new Bootstrap.Tooltip(tooltipTriggerEl)
	});
	
	if (document.getElementById(searchBarId)) {
		render(<SearchBar />, document.getElementById(searchBarId));
	}
	
	window.Bootstrap = Bootstrap;
});

setInterval(function(){
	axios.get(buildGenericApiUrl('api', 'ping'))
}, 120000)