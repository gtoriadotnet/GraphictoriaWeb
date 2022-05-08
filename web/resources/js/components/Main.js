// © XlXi 2021
// Graphictoria 5

import $ from 'jquery';
import * as Bootstrap from 'bootstrap';

import React from 'react';
import { render } from 'react-dom';

import SearchBar from './SearchBar';

const searchBarId = 'graphictoria-nav-searchbar';

$(document).ready(function() {
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new Bootstrap.Tooltip(tooltipTriggerEl)
	});
	
	if (document.getElementById(searchBarId)) {
		render(<SearchBar />, document.getElementById(searchBarId));
	}
});