/*
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Deployer from '../components/Deployer';

const deployerId = 'vb-deployer';

$(document).ready(function() {
	if (document.getElementById(deployerId)) {
		render(<Deployer />, document.getElementById(deployerId));
	}
});