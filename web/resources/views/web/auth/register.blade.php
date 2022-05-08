@php
	$fields = [
		'username' => 'Username',
		'email' => 'Email',
		'password' => 'Password',
		'password_confirmation' => 'Confirm Password'
	];
@endphp

@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container graphictoria-center-vh">
	<x-card>
		<x-slot name="title">
			<i class="fas fa-user-circle"></i> REGISTER
		</x-slot>
		<x-slot name="body">
			<div class="p-sm-2 d-flex flex-column justify-content-center">
				<div class="px-3 mb-10">
					<div class="alert alert-warning graphictoria-alert graphictoria-error-popup">
						<p class="mb-0">Make sure your password is unique!</p>
					</div>
				</div>
				@if ($errors->any())
					<div class="px-3 mb-10">
						<div class="alert alert-danger graphictoria-alert graphictoria-error-popup">{{ $errors->first() }}</div>
					</div>
				@endif
				
				<form method="POST" action="{{ route('auth.register.submit') }}">
					@csrf
					@foreach($fields as $field => $label)
						<input type="{{ $field == 'password_confirmation' ? 'password' : $field }}" @class(['form-control', 'mb-2', 'is-invalid'=>($errors->first($field) != null)]) placeholder="{{ $label }}" name="{{ $field }}" :value="old($field)" />
					@endforeach
					<button class="btn btn-success px-5 mt-3" type="submit">Sign Up</button>
				</form>
				<a href="{{ route('auth.login.index') }}" class="text-decoration-none fw-normal center" target="_blank">Already have an account?</a>
				
				<p class="text-muted mt-3">By creating an account, you agree to our <a href="/legal/terms-of-service" class="text-decoration-none fw-normal" target="_blank">Terms of Service</a> and our <a href="/legal/privacy-policy" class="text-decoration-none fw-normal" target="_blank">Privacy Policy</a>.</p>
			</div>
		</x-slot>
		<x-slot name="footer"></x-slot>
	</x-card>
</div>
@endsection
