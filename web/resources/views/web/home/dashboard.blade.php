@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-specific')
<script src="{{ mix('js/Dashboard.js') }}"></script>
@endsection

@section('content')
<div class="container-lg my-2">
	<div class="row">
		<div class="col-md-3">
			<h4>Hello, {{ Auth::user()->username }}!</h4>
			<div class="card text-center">
				<img src="{{ asset('/images/testing/avatar.png') }}" class="img-fluid gt-charimg" />
			</div>
			
			<h4 class="mt-3">Blog</h4>
			<div class="card p-2">
				<ul class="text-center list-unstyled mb-0">
					<li class="pb-2"><a href="#" class="text-decoration-none fw-normal"><i class="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
					<li class="pb-2"><a href="#" class="text-decoration-none fw-normal"><i class="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
					<li class="pb-2"><a href="#" class="text-decoration-none fw-normal"><i class="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
				</ul>
				<div class="text-left">
					<a href="https://blog.gtoria.net" class="text-decoration-none fw-normal" target="_blank">More <i class="fa-solid fa-caret-right"></i></a>
				</div>
			</div>
		</div>
		
		<div class="col-md-9">
			<h4 class="mt-3 mt-md-0">Recently Played</h4>
			<div class="card p-2 mb-3">
				Content here.
			</div>
			
			<div id="gt-dash-feed"></div>
		</div>
	</div>
</div>
@endsection