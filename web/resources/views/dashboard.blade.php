@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-lg my-2">
	<h4>Hello, {{ Auth::user()->username }}!</h4>
	<div class="row">
		<div class="col-md-3">
			<div class="card text-center">
				<img src="{{ asset('/images/testing/avatar.png') }}" class="img-fluid gt-charimg" />
				<p class="mb-3 px-3">"abcd"</p>
			</div>
			
			<x-MiniCard class="mt-3 d-none d-md-flex">
				<x-slot name="title">
					Blog
				</x-slot>
				<x-slot name="body">
					<ul class="text-center list-unstyled mb-1">
						<li class="pb-2"><a href="#" class="text-decoration-none fw-normal"><i class="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
						<li class="pb-2"><a href="#" class="text-decoration-none fw-normal"><i class="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
						<li class="pb-2"><a href="#" class="text-decoration-none fw-normal"><i class="fa-solid fa-circle-right"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</a></li>
					</ul>
					<div class="text-left px-2">
						<a href="https://blog.gtoria.net" class="text-decoration-none fw-normal" target="_blank">More <i class="fa-solid fa-caret-right"></i></a>
					</div>
				</x-slot>
			</x-MiniCard>
		</div>
		
		<div class="col-md-9 mt-3 mt-md-0">
			<x-MiniCard class="d-none d-md-flex mb-3">
				<x-slot name="title">
					Recently Played
				</x-slot>
				<x-slot name="body">
					Content here.
				</x-slot>
			</x-MiniCard>
			
			<h4>My Feed</h4>
			<div class="card mb-2">
				<div class="input-group p-2">
					<input type="text" class="form-control" placeholder="What are you up to?" area-label="What are you up to?"/>
					<button class="btn btn-secondary" type="submit">Share</button>
				</div>
			</div>
			<div class="d-flex">
				<x-loader />
			</div>
		</div>
	</div>
</div>
@endsection