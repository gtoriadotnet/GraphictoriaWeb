@php
	$fields = [
		'email' => 'Email Address'
	];
@endphp

@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container graphictoria-center-vh">
	<x-card>
		<x-slot name="title">
			<i class="fa-solid fa-magnifying-glass"></i> FORGOT PASSWORD
		</x-slot>
		<x-slot name="body">
			<div class="p-2 mb-2 d-flex flex-column justify-content-center">
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
				
				<h5 class="m-0">Forgot your password? No problem!</h5>
				<p class="mb-3">Enter the email address associated with your account and we'll send you a reset link.</p>
				
				<form method="POST" action="{{ route('auth.password.forgot-submit') }}">
					@csrf
					@foreach($fields as $field => $label)
						<input type="{{ $field }}" @class(['form-control', 'mb-2', 'is-invalid'=>($errors->first($field) != null)]) placeholder="{{ $label }}" name="{{ $field }}" :value="old($field)" />
					@endforeach
					<a href="{{ route('auth.login.index') }}" class="btn btn-secondary px-5" ><i class="fa-solid fa-angles-left"></i> Back</a>&nbsp;
					<button class="btn btn-primary px-5" type="submit">Send Reset Link</button>
				</form>
			</div>
		</x-slot>
		<x-slot name="footer"></x-slot>
	</x-card>
</div>
@endsection