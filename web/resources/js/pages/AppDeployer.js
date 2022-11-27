/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Deployer from '../components/Deployer';

const deployerId = 'gt-deployer';

$(document).ready(function() {
	if (document.getElementById(deployerId)) {
		render(<Deployer />, document.getElementById(deployerId));
	}
});