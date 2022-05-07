@extends('layouts.app')

@section('title', 'Internal Server Error')

@section('content')
<div class="container graphictoria-center-vh">
	<x-card title="INTERNAL SERVER ERROR">
		<x-slot name="body">
			Oops, we ran into an issue while trying to process your request, please try again later in a few minutes. If the issue persists after a few minutes, please contact us at <a href="mailto:support@gtoria.net" class="fw-bold text-decoration-none">support@gtoria.net</a>.
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
