/*
	Copyright © XlXi 2023
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import ModerationAutofills from '../components/ModerationAutofills';

const autofillId = 'vb-mod-autofill';

$(document).ready(function() {
	if (document.getElementById(autofillId)) {
		render(<ModerationAutofills />, document.getElementById(autofillId));
	}
});