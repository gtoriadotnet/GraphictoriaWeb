@extends('layouts.app')

@section('title', 'Bad Request')

@section('content')
<div class="container graphictoria-center-vh">
	<x-card title="BAD REQUEST">
		<x-slot name="body">
			There was a problem with your request. If you believe this is an error on our part, contact us at <a href="mailto:support@gtoria.net" class="fw-bold text-decoration-none">support@gtoria.net</a>!
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
