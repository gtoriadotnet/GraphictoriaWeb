import $ from 'jquery';
import * as Bootstrap from 'bootstrap';

$(document).ready(function() {
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	  return new Bootstrap.Tooltip(tooltipTriggerEl)
	});
});