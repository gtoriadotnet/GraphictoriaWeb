/*
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Transactions from '../components/Transactions';

const transactionsId = 'vb-transactions';

$(document).ready(function() {
	if (document.getElementById(transactionsId)) {
		render(<Transactions />, document.getElementById(transactionsId));
	}
});