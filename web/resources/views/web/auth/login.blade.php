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
				<div class="col-md-8 mb-2 d-flex flex-column justify-content-center">
					@if ($errors->any())
						<div class="px-3 mb-10">
							<div class="alert alert-danger graphictoria-alert graphictoria-error-popup">{{ $errors->first() }}</div>
						</div>
					@endif
					
					@if(session('status'))
						<div class="px-3 mb-10">
							<div class="alert alert-success graphictoria-alert graphictoria-error-popup">{{ session('status') }}</div>
						</div>
					@endif
					
					<form method="POST" action="{{ route('auth.login.submit') }}">
						@csrf
						@foreach($fields as $field => $label)
							<input type="{{ $field }}" @class(['form-control', 'mb-2', 'is-invalid'=>($errors->first($field) != null)]) placeholder="{{ $label }}" name="{{ $field }}" :value="old($field)" />
						@endforeach
						<div class="mb-2">
							<input class="form-check-input" type="checkbox" value="" id="remember" name="remember">
							<label class="form-check-label" for="remember">
								Remember Me
							</label>
						</div>
						<button class="btn btn-primary px-5" type="submit">Sign In</button>
					</form>
					
					<a href="{{ route('auth.password.forgot') }}" class="text-decoration-none fw-normal center">Forgot your password?</a>
				</div>
				<div class="col">
					<h5>New to Graphictoria?</h5>
					<p>Creating an account takes less than a minute, and you can join a community of 10k+ users for <b>completely free</b>.<br/><a href="{{ route('auth.register.index') }}" class="btn btn-sm btn-success mt-2">Sign Up</a></p>
				</div>
			</div>
		</x-slot>
		<x-slot name="footer"></x-slot>
	</x-card>
</div>
@endsection
