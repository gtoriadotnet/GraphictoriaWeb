@php
	$fields = [
		'password' => 'New Password',
		'password_confirmation' => 'Confirm New Password'
	];
@endphp

@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container graphictoria-center-vh">
	<x-card>
		<x-slot name="title">
			<i class="fa-solid fa-magnifying-glass"></i> RESET PASSWORD
		</x-slot>
		<x-slot name="body">
			<div class="p-2 mb-2 d-flex flex-column justify-content-center">
				@if ($errors->any())
					<div class="px-3 mb-10">
						<div class="alert alert-danger graphictoria-alert graphictoria-error-popup">{{ $errors->first() }}</div>
					</div>
				@endif
				
				<form method="POST" action="{{ route('password.update') }}">
					@csrf
					<input type="hidden" name="token" value="{{ $request->route('token') }}" />
					@foreach($fields as $field => $label)
						<input type="{{ $field == 'password_confirmation' ? 'password' : $field }}" @class(['form-control', 'mb-2', 'is-invalid'=>($errors->first($field) != null)]) placeholder="{{ $label }}" name="{{ $field }}" :value="old($field)" />
					@endforeach
					<button class="btn btn-primary px-5" type="submit">Change Password</button>
				</form>
			</div>
		</x-slot>
		<x-slot name="footer"></x-slot>
	</x-card>
</div>
@endsection