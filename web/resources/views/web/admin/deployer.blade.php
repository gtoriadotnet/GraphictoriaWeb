@extends('layouts.admin')

@section('title', 'App Deployer')

@section('page-specific')
<!-- Secure Page JS -->
<script src="{{ mix('js/adm/AppDeployer.js') }}"></script>
@endsection

@push('content')
<div class="container">
	<div class="alert alert-warning virtubrick-alert virtubrick-error-popup">Ensure the RCC version compatibility security settings and api keys are synced before deploying, else you may completely brick games or api calls.</div>
	<h4 class="mt-1">App Deployer</h4>
	<div class="card p-2" id="vb-deployer">
		<x-loader />
	</div>
</div>
@endpush