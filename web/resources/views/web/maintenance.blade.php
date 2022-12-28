@php
	$buttons = str_split('VirtuBrick')
@endphp

@extends('layouts.app')
@nofooter

@section('title', 'Site Offline')
@section('description', config('app.name') . ' is currently under maintenance, check back later!')

@section('theme', 'dark')

@section('page-specific')
<script src="{{ mix('js/Maintenance.js') }}"></script>
@endsection

@section('content')
<div class="vbrick-maintenance-background"></div>
<div class="text-center m-auto container vbrick-maintenance-form">
	<h1>{{ config('app.name') }} is currently under maintenance.</h1>
	<h4>Our cyborg team of highly trained code-monkes are working to make {{ config('app.name') }} better. We'll be back soon!</h4>
	@if(!$hideLogin)
		<div class="input-group mt-5 d-none d-md-flex" id="vbrick_mt_buttons">
			<input type="password" class="form-control" placeholder="Password" autoComplete="off" />
			@foreach($buttons as $index => $button)
				<button class="btn btn-secondary" type="button" name="vb_mtbtn{{ $index }}">{{ $button }}</button>
			@endforeach
		</div>
	@endif
</div>
@endsection