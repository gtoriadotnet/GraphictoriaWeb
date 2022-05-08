@php
	$noFooter = true;
	$noNav = true;
@endphp

@extends('layouts.app')

@section('title', 'Moderation Notice')

@section('content')
<div class="container m-auto">
	<x-card class="graphictoria-moderation-card">
		<x-slot name="title">
			MODERATION NOTICE
		</x-slot>
		<x-slot name="body">
			<div class="p-2 mb-2 d-flex flex-column justify-content-center">
				<p>Your account has been suspended for violating our Terms of Service.</p>
				<div class="my-3">
					<p><b>Suspention Date:</b> 5/6/2022 9:35 PM</p>
					<p><b>Note:</b> testing</p>
				</div>
			</div>
		</x-slot>
		<x-slot name="footer">
			<p>By checking the "I Agree" checkbox below, you agree to abide by Graphictoria's Terms of Service. Your account will be permantently suspended if you continue breaking the Terms of Service.</p>
			<form>
				<div class="my-2">
					<input class="form-check-input" type="checkbox" value="" id="agree" name="agree">
					<label class="form-check-label" for="agree">
						I Agree
					</label>
				</div>
				<button class="btn btn-primary">REACTIVATE</button>
			</form>
			
			<p>You will be able to reactivate your account in <b>0 Seconds</b>.</p>
			<p class="text-muted">If you believe you have been unfairly moderated, please contact us at contact us at <a href="mailto:support@gtoria.net" class="fw-bold text-decoration-none">support@gtoria.net</a> and we'll be happy to help.</p>
		</x-slot>
	</x-card>
</div>
@endsection
