@extends('layouts.admin')

@section('title', 'Upload Asset')

@section('page-specific')
<!-- Secure Page JS -->
<script src="{{ mix('js/adm/ManualAssetUpload.js') }}"></script>
@endsection

@push('content')
	<div class="container-md">
		<x-admin.navigation.asset-uploader />
		<h4>Upload Asset</h4>
		<div class="row" id="vb-manual-assetupload">
			<x-loader />
		</div>
	</div>
@endpush