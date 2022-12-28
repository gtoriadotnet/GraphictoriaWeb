@extends('layouts.admin')

@section('title', 'Site Configuration')

@section('page-specific')
<!-- Secure Page JS -->
<script src="{{ mix('js/adm/SiteConfiguration.js') }}"></script>
@endsection

@push('content')
	<div class="container-md">
		<h4>Site Configuration</h4>
		<div class="card">
			
		</div>
	</div>
@endpush