@php
	$noFooter = true;
	$noNav = true;
@endphp

@extends('layouts.app')

@section('title', 'Captcha')

@section('extra-headers')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="container m-auto">
	<x-card>
		<x-slot name="title">
			BOOP BOOP. BEEP?
		</x-slot>
		<x-slot name="body">
			<div class="p-2 mb-2 d-flex flex-column justify-content-center">
				<h5>Are you a ROBOT?</h5>
				<p>We've detected unusual activity coming from your network. To protect ourselves from DDoS attacks and other forms of web abuse, we've blocked your request. Solve the captcha below to regain access to the website.</p>
				
				<div class="mt-3">
					@if ($errors->any())
						<div class="px-3 mb-10">
							<div class="alert alert-danger graphictoria-alert graphictoria-error-popup">{{ $errors->first() }}</div>
						</div>
					@endif
					
					<form method="POST" action="{{ route('auth.protection.bypass') }}">
						@csrf
						
						<input type="hidden" name="ReturnUrl" value="{{ request()->input('ReturnUrl') }}" />
						
						<div class="d-flex">
							<div class="g-recaptcha mb-2 mx-auto" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
						</div>
						
						<button class="btn btn-primary px-5" type="submit">Continue</button>
					</form>
				</div>
			</div>
		</x-slot>
		<x-slot name="footer">
			<p class="text-muted">If this page continues to show up after you solve the captcha, please contact us at contact us at <a href="mailto:support@gtoria.net" class="fw-bold text-decoration-none">support@gtoria.net</a> and we'll be happy to help.</p>
		</x-slot>
	</x-card>
</div>
@endsection
