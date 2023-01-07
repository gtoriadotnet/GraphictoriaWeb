@extends('layouts.admin')

@section('title', 'Find User')

@php
	if(isset($users) && $users->count() == 0)
		$error = 'No users found.';
@endphp

@push('content')
	<div class="container-md">
		<x-admin.navigation.user-search />
		<h4>Find User</h4>
		@if(isset($error))
			<div class="alert alert-danger virtubrick-alert virtubrick-error-popup">{{ $error }}</div>
		@endif
		<div class="card p-3">
			<div class="row">
				<x-admin.user-search-input id="userid" definition="User ID" />
				<x-admin.user-search-input id="username" definition="Username" />
				@owner
					<x-admin.user-search-input id="ipaddress" definition="IP Address" />
				@endowner
			</div>
		</div>
		@if(isset($users) && $users->count() > 0)
			<h4 class="mt-2">Find User Results</h4>
			<div class="card">
				<table class="table virtubrick-table">
					<thead>
						<tr>
							<th scope="col">User</th>
							<th scope="col">ID</th>
							<th scope="col">Email</th>
							<th scope="col">Moderation Status</th>
							<th scope="col">Rolesets</th>
							<th scope="col">Created</th>
							<th scope="col">Last Online</th>
							<th scope="col">Last Location</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
							@php
								if(isset($isIpSearch))
									$user = $user->user;
								
								$rolesets = $user->getRolesets();
								$rolesetCount = $rolesets->count();
							@endphp
							<tr class="align-middle">
								<th scope="col">
									<a href="{{ route('admin.useradmin', ['ID' => $user->id]) }}" class="text-decoration-none">
										<x-user-circle :user="$user" :size=40 />
									</a>
								</th>
								<th scope="col">{{ $user->id }}</th>
								<th scope="col">{{ Auth::user()->hasRoleset('Owner') ? $user->email : $user->getCensoredEmail() }}</th>
								<th scope="col"><x-admin.moderation-status :user="$user" /></th>
								<th scope="col">
									@if($rolesetCount > 0)
										@php
											$rolesetLoops = 0;
										@endphp
										@foreach($rolesets->get() as $roleset)
											@php
												$rolesetLoops += 1;
												$notLast = $rolesetLoops != $rolesetCount;
											@endphp
											{{ $roleset->roleset->Name . ($notLast ? ',' : '')}}
											@if($notLast)
												<br/>
											@endif
										@endforeach
									@else
										<i class="text-muted">None</i>
									@endif
								</th>
								<th scope="col">{{ $user->created_at->isoFormat('l LT') }}</th>
								<th scope="col">{{ $user->last_seen->isoFormat('l LT') }}</th>
								<th scope="col">Website (TODO)</th>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endif
	</div>
@endpush