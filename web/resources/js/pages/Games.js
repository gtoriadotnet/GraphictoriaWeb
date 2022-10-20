/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Games from '../components/Games';

const gamesId = 'gt-games-main';

$(document).ready(function() {
	if (document.getElementById(gamesId)) {
		render(<Games />, document.getElementById(gamesId));
	}
});