/*
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import AvatarEditor from '../components/AvatarEditor';

const editorId = 'vb-avatar-editor';

$(document).ready(function() {
	if (document.getElementById(editorId)) {
		let eElem = document.getElementById(editorId);
		render(<AvatarEditor element={ eElem }/>, eElem);
	}
});