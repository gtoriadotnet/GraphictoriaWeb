/*
	Copyright © XlXi 2023
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import ManualAssetUpload from '../components/ManualAssetUpload';

const assetUploadId = 'vb-manual-assetupload';

$(document).ready(function() {
	if (document.getElementById(assetUploadId)) {
		render(<ManualAssetUpload />, document.getElementById(assetUploadId));
	}
});