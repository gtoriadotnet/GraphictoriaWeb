@php
	$noNav = true;
	$noFooter = true;
@endphp

@extends('layouts.app', ['title' => 'Javascript'])

@section('title', 'Javascript')

@section('page-specific')
	<style>
		/* .graphictoria-nojs */
		
		body {
			background-image: url("/Images/Backgrounds/NoJs.png");
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center;
			color: white;
		}
	</style>
@endsection

@section('content')
	<div class="text-center m-auto container">
		<h2>Uh oh!</h2>
		<h5>Your browser doesn't seem to support JavaScript! Please upgrade your browser to use Graphictoria.</h5>
		<hr class="mx-auto" width="20%"/>
		<h4>Javascript Compatible Browsers:</h4>
		<ul class="list-unstyled">
			<li><a href="https://www.google.com/chrome/" class="fw-bold text-decoration-none">Google Chrome</a></li>
			<li><a href="https://www.mozilla.org/en-US/firefox/new/" class="fw-bold text-decoration-none">Mozilla Firefox</a></li>
			<li><a href="https://www.microsoft.com/en-us/edge" class="fw-bold text-decoration-none">Microsoft Edge</a></li>
			<li><a href="https://brave.com/download/" class="fw-bold text-decoration-none">Brave</a></li>
			<li><a href="https://www.opera.com/gx" class="fw-bold text-decoration-none">Opera</a></li>
		</ul>
	</div>
@endsection
