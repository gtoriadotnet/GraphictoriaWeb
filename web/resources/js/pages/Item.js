/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Comments from '../components/Comments';
import PurchaseButton from '../components/PurchaseButton';
import ThumbnailTool from '../components/ThumbnailTool';

const purchaseId = 'gt-purchase-button';
const commentsId = 'gt-comments';
const thumbnailId = 'gt-thumbnail';

$(document).ready(function() {
	if (document.getElementById(commentsId)) {
		let cElem = document.getElementById(commentsId);
		render(<Comments element={ cElem } />, cElem);
	}
	if (document.getElementById(purchaseId)) {
		render(<PurchaseButton />, document.getElementById(purchaseId));
	}
	if (document.getElementById(thumbnailId)) {
		let tElem = document.getElementById(thumbnailId);
		render(<ThumbnailTool element={ tElem } />, tElem);
	}
});