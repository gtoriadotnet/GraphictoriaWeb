@php
	$fields = [
		'username' => 'Username',
		'password' => 'Password'
	];
@endphp

@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container graphictoria-center-vh">
	<x-card>
		<x-slot name="title">
			<i class="fas fa-user-circle"></i> SIGN IN
		</x-slot>
		<x-slot name="body">
			<div class="p-2 row">
				<div class="col-md-8 mb-2">
					@if($errors->any())
						<div class="px-5 mb-10">
							<div class="alert alert-danger graphictoria-alert graphictoria-error-popup">{{ $errors->first() }}</div>
						</div>
					@endif
					
					<form method="POST" action="{{ url('/login') }}">
						@csrf
						@foreach($fields as $field => $label)
							<input type="{{ $field }}" @class(['form-control', 'mb-4', 'is-invalid'=>($errors->first($field) != null)]) placeholder="{{ $label }}" name="{{ $field }}" value="{{ old($field) }}" />
						@endforeach
						<button class="btn btn-primary px-5" type="submit">SIGN IN</button>
					</form>
					
					<a href="/passwordreset" class="text-decoration-none fw-normal center" target="_blank">Forgot your password?</a>
				</div>
				<div class="col">
					<h5>New to Graphictoria?</h5>
					<p>Creating an account takes less than a minute, and you can join a community of 8k+ users for <b>completely free</b>.<br/><a href="/register" class="btn btn-sm btn-success mt-2" target="_blank">Sign Up</a></p>
				</div>
			</div>
		</x-slot>
		<x-slot name="footer"></x-slot>
	</x-card>
</div>
@endsection
