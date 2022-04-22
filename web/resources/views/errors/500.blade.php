@extends('layouts.app')

@section('title', 'Internal Server Error')

@section('content')
<div class="container graphictoria-center-vh">
	@env(['staging', 'local'])
		<br />
	@endenv
	<x-card title="INTERNAL SERVER ERROR">
		<x-slot name="body">
			Oops, we ran into an issue while trying to process your request, please try again later in a few minutes. If the issue persists after a few minutes, please contact us at <a href="mailto:support@gtoria.net" class="fw-bold text-decoration-none">support@gtoria.net</a>.
			@env(['staging', 'local'])
				@if(isset($stack))
					<div class="border border-primary bg-dark p-3 m-4">
						<code>
							STACK TRACE<br/>{{ str_repeat('-', 15) }}<br/>{{ $stack }}
						</code>
					</div>
				@endif
			@endenv
		</x-slot>
		<x-slot name="footer">
			<div class="mt-2">
				<a class="btn btn-primary px-4 me-2" href="{{ url('/') }}">Home</a>
				<a class="btn btn-secondary px-4" onclick="history.back();return false;">Back</a>
			</div>
		</x-slot>
	</x-card>
	@env(['staging', 'local'])
		<br />
	@endenv
</div>
@endsection
