@extends('layouts.app')

@section('title', 'Not Implemented')

@section('content')
<div class="container virtubrick-center-vh">
	<x-card title="NOT IMPLEMENTED">
		<x-slot name="body">
			todo
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
