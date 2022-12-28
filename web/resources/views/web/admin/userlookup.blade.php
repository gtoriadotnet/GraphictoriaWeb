@extends('layouts.admin')

@section('title', 'Lookup Tool')

@php
	if(isset($users) && count($users) == 0)
		$error = 'No users found.';
@endphp

@push('content')
	<div class="container-md">
		<x-admin.navigation.user-search />
		<h4>Lookup Tool</h4>
		@if(isset($error))
			<div class="alert alert-danger virtubrick-alert virtubrick-error-popup">{{ $error }}</div>
		@endif
		<div class="card p-3">
			<div class="row">
				<div class="col-3">
					<form method="POST" action="{{ route('admin.userlookup') }}" enctype="multipart/form-data">
						@csrf
						<textarea type="text" class="form-control" name="lookup" placeholder="Usernames, one per line." rows="10">{{ isset($input) ? $input : '' }}</textarea>
						<button type="submit" class="btn btn-primary mt-1">Search</button>
					</form>
				</div>
				@if(isset($users) && count($users) > 0)
					<div class="col-3">
						<table class="table virtubrick-table">
							<thead>
								<tr>
									<th scope="col">User Id</th>
									<th scope="col">User</th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $user)
									<tr>
										@if($user['found'])
											<th scope="col">{{ $user['user']->id }}</th>
											<th scope="col">
												<a href="{{ route('admin.useradmin', ['ID' => $user['user']->id]) }}" class="text-decoration-none">
													<x-user-circle :user="$user['user']" :size=37 />
												</a>
											</th>
										@else
											<th scope="col"></th>
											<th scope="col">Unable to find {{ $user['username'] }}</th>
										@endif
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		</div>
	</div>
@endpush