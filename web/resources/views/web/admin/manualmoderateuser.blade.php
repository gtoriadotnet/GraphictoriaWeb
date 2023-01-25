@extends('layouts.admin')

@section('title', 'Moderate User (' . $user->username . ')')

@section('page-specific')
<!-- Secure Page JS -->
<script src="{{ mix('js/adm/ManualUserModeration.js') }}"></script>
@endsection

@push('content')
	<div class="container-md">
		<x-admin.navigation.user-admin :uid="$user->id" />
		<h4 class="mb-0">Moderate User</h4>
		<p class="mb-2">{{ $user->username }} <a href="{{ $user->getProfileUrl() }}" class="text-decoration-none">home page</a></p>
		@if($errors->any())
			@foreach($errors->all() as $error)
				<div class="alert alert-danger virtubrick-alert virtubrick-error-popup">{{ $error }}</div>
			@endforeach
		@endif
		@if(isset($success))
			<div class="alert alert-success virtubrick-alert virtubrick-error-popup">{{ $success }}</div>
		@endif
		<div class="card me-2 p-3">
			<form method="POST" action="{{ route('admin.manualmoderateuser', ['ID' => $user->id]) }}" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="col-3">
						<label class="form-label">Account State override:</label>
						@foreach(\App\Models\PunishmentType::all() as $punishmentType)
							<div class="form-check">
								<input class="form-check-input" type="radio" name="moderate-action" id="moderate-action-{{ Str::slug($punishmentType->name, '-') }}" value="{{ $punishmentType->id }}">
								<label class="form-check-label" for="moderate-action-{{ Str::slug($punishmentType->name, '-') }}">{{ $punishmentType->name }}</label>
							</div>
						@endforeach
					</div>
					<div class="col-3">
						<label class="form-label">Auto-fill fields:</label>
						<div id="vb-mod-autofill"></div>
					</div>
					<div class="col-6">
						<label for="user-note" class="form-label">Note to {{ $user->username }}:</label>
						<textarea type="text" class="form-control mb-3" name="user-note" id="user-note"></textarea>
						
						<label for="internal-note" class="form-label">Internal Moderation Note:</label>
						<textarea type="text" class="form-control mb-3" name="internal-note" id="internal-note"></textarea>
						
						<div class="form-check mb-2">
							<input class="form-check-input" type="checkbox" value="1" name="scrub-username" id="scrub-username">
							<label class="form-check-label" for="scrub-username"><b>Scrub Username:</b> This will set the user's name to [Content Deleted] and hide the user's name history.</label>
						</div>
						@admin
							<div class="form-check mb-2">
								<input class="form-check-input" type="checkbox" value="1" name="poison-ban" id="poison-ban">
								<label class="form-check-label" for="poison-ban"><b>Posion:</b> Disables new account creation on the user's IP address.</label>
							</div>
							<div class="form-check mb-2">
								<input class="form-check-input" type="checkbox" value="1" name="ip-ban" id="ip-ban">
								<label class="form-check-label" for="ip-ban"><b>IP Ban:</b> Restricts the user from accessing the website. If selected, you will be prompted with options for this punishment.</label>
							</div>
						@else
							<p class="text-warning">Please contact an administrator or higher if you believe this user should be poison or IP banned.<br/>If you believe this user to be suicidal, contact an owner and options will be explored to get this user help.</p>
						@endadmin
						
						<button type="submit" class="btn btn-danger w-100 mt-1">Ban User</button>
					</div>
				</div>
			</form>
		</div>
		
		<h4 class="mt-3">Past Punishments</h4>
		<x-admin.user-punishments :user="$user" />
	</div>
@endpush