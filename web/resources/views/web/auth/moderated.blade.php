@extends('layouts.app')
@section('theme', 'light')
@nonav
@nofooter

@section('content')
<div class="container m-auto">
	<div class="card p-3 my-4 virtubrick-moderation-card">
		<h3>{{ $punishment->punishment_type->label }}</h3>
		<p>
			Your account has been {{ $punishment->isDeletion() ? 'closed ' : 'temporarily restricted' }} for violating our Terms of Service.
			@if(!$punishment->isDeletion())
				Your account will be terminated if you do not abide by the rules.
			@endif
		</p>
		
		<div class="my-3">
			<p><b>Reviewed:</b> {{ $punishment->reviewed() }}</p>
			<p><b>Moderator Note:</b> {{ $punishment->user_note }}</p>
		</div>
		
		@foreach($punishment->context as $context)
			<div class="card bg-secondary p-2 mb-2 border-1">
				<p><b>Reason:</b> {{ $context->user_note }}</p>
				@if($context->description)
					<p><b>Offensive Item:</b> {{ $context->description }}</p>
				@endif
				@if($context->content_hash)
					<img src="{{ route('content', $context->content_hash) }}" class="img-fluid" width="210" height="210"/>
				@endif
			</div>
		@endforeach
		
		<div class="text-center">
			@if($punishment->expired())
				<p>By checking the "I Agree" checkbox below, you agree to abide by {{ config('app.name') }}'s Terms of Service.</p>
				<form method="POST" action="{{ route('punishment.reactivate') }}" class="mt-2">
					@csrf
					<div class="mb-2">
						<input class="form-check-input" type="checkbox" value="" id="vb-reactivation-checkbox">
						<label class="form-check-label" for="vb-reactivation-checkbox">
							I Agree
						</label>
					</div>
					<button class="btn btn-success" id="vb-reactivation-button" disabled>Re-activate My Account</button>
				</form>
				<script>
					document.getElementById('vb-reactivation-checkbox').addEventListener('change', (event) => {
						document.getElementById('vb-reactivation-button').disabled = !event.currentTarget.checked;
					});
				</script>
			@elseif(!$punishment->isDeletion())
				<p>You will be able to reactivate your account in <b>{{ $punishment->expiration->diffForHumans(['syntax' => Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}</b>.</p>
			@endif
			
			<a class="btn btn-primary my-2" href="{{ route('auth.logout') }}">Logout</a>
			
			<p class="text-muted">If you believe you have been unfairly moderated, please contact us at contact us at <a href="mailto:support@virtubrick.net" class="fw-bold text-decoration-none">support@virtubrick.net</a> and we'll be happy to help.</p>
		</div>
	</div>
</div>
@endsection
