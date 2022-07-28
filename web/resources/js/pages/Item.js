// © XlXi 2022
// Graphictoria 5

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Comments from '../components/Comments';
import PurchaseButton from '../components/PurchaseButton';

const purchaseId = 'gt-purchase-button';
const commentsId = 'gt-comments';

$(document).ready(function() {
	if (document.getElementById(commentsId)) {
		render(<Comments />, document.getElementById(commentsId));
	}
	if (document.getElementById(purchaseId)) {
		render(<PurchaseButton />, document.getElementById(purchaseId));
	}
});