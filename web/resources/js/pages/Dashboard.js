// © XlXi 2021
// Graphictoria 5

import $ from 'jquery';
import * as Bootstrap from 'bootstrap';

import React from 'react';
import { render } from 'react-dom';

import Feed from '../Components/Feed';

const feedId = 'gt-dash-feed';

$(document).ready(function() {
	if (document.getElementById(feedId)) {
		render(<Feed />, document.getElementById(feedId));
	}
});