@php
$errorTitles = ['OH NOES!!!', 'BZZT', 'ERROR', 'UH OH.'];
@endphp

@extends('layouts.app')

@section('title', 'Not Found')

@section('content')
<div class="container graphictoria-center-vh">
	<x-card title="{{ $errorTitles[array_rand($errorTitles)] }}">
		<x-slot name="body">
			We've looked far and wide and weren't able to find the page you were looking for. If you believe this is an error, contact us at <a href="mailto:support@gtoria.net" class="fw-bold text-decoration-none">support@gtoria.net</a>!
		</x-slot>
		<x-slot name="footer">
			<div class="mt-2">
				<a class="btn btn-primary px-4 me-2" href="{{ url('/') }}">Home</a>
				<a class="btn btn-secondary px-4" onclick="history.back();return false;">Back</a>
			</div>
		</x-slot>
	</x-card>
</div>
@endsection
