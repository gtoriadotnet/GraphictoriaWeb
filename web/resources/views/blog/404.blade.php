@extends('layouts.blog')

@section('content')
<div class="container text-center my-5">
	<h1>404</h1>
	<h3>We weren't able to find the page you were looking for.</h3>
	<a class="text-decoration-none fw-normal" href="{{ route('blog.home') }}">Go Home</a>
</div>
@endsection