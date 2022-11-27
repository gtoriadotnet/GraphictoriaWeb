/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Comments from '../components/Comments';
import ThumbnailTool from '../components/ThumbnailTool';
import PlaceButtons from '../components/PlaceButtons';

const thumbnailId = 'gt-thumbnail';
const buttonsId = 'gt-place-buttons';
const commentsId = 'gt-comments';

$(document).ready(function() {
	if (document.getElementById(thumbnailId)) {
		let tElem = document.getElementById(thumbnailId);
		render(<ThumbnailTool element={ tElem } placeholder="/images/busy/game.png" width="640" height="360" />, tElem);
	}
	if (document.getElementById(buttonsId)) {
		let bElem = document.getElementById(buttonsId);
		render(<PlaceButtons element={ bElem } />, bElem);
	}
	if (document.getElementById(commentsId)) {
		let cElem = document.getElementById(commentsId);
		render(<Comments element={ cElem } />, cElem);
	}
});