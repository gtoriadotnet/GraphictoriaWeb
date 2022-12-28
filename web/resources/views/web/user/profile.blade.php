{{-- XlXi: References lol --}}
{{-- XlXi: https://cubash.com/@Icseon --}}
{{-- XlXi: https://youtu.be/nouY1ugddcI?t=583 --}}

@extends('layouts.app')

@section('title', $title)

@section('quick-admin')
<li class="nav-item">
	<a href="{{ route('admin.useradmin', ['ID' => $user->id]) }}" class="nav-link py-0">User Admin</a>
</li>
@endsection

@section('content')
<div class="container-lg my-4 virtubrick-smaller-page">
	{{-- User pane --}}
	<div class="card p-2">
		<div class="d-flex">
			<div class="pe-3">
				<img class="img-fluid border virtubrick-user-circle m-1" src="{{ asset('/images/testing/headshot.png') }}" alt="User avatar of {{ $user->username }}" width="120px" />
			</div>
			<div class="flex-fill d-flex flex-column p-2">
				{{-- TODO: XlXi: Advanced presence --}}
				<h4 class="mb-0">
					{{ $user->username }}
					<span @class([
						'text-muted' => !$user->isOnline(),
						'text-success' => $user->isOnline()
					])>({{ $user->isOnline() ? 'Online' : 'Offline' }})</span>
				</h4>
				<p>"This is my current status!"</p>
				<div class="d-md-flex mt-auto">
					<a href="#" class="btn btn-primary ms-auto">Send Message</a>
					<a href="#" class="btn btn-success ms-1">Add Friend</a>
				</div>
			</div>
		</div>
	</div>
	
	{{-- About pane --}}
	<h4 class="mt-2">About</h4>
	<div class="card p-2">
		@if($user->biography)
			<p>{{ $user->biography }}</p>
		@else
			<i class="text-muted">This user has no description.</i>
		@endif
		<hr class="my-2" />
		<div class="d-flex">
			{{-- TODO: XlXi: convert this to a route --}}
			<a href="https://www.virtubrick.local/report/user/notfinishedtodo" target="_blank" class="text-decoration-none link-danger ms-auto">Report <i class="fa-solid fa-circle-exclamation"></i></a>
		</div>
	</div>
	
	{{-- Games pane --}}
	<h4 class="mt-2">Games</h4>
	<div class="card p-3">
		<div>
			{{-- XlXi: just reuse the shop cards for this lol --}}
			{{-- XlXi: https://cdn.discordapp.com/attachments/845538783592054795/1028135570335092826/unknown.png --}}
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			{{-- Badges pane --}}
			<h4 class="mt-2">Badges</h4>
			<div class="card p-2">
				{{-- TODO: XlXi: badges --}}
			</div>
		</div>
		<div class="col-md-6">
			{{-- Friends pane --}}
			<h4 class="mt-2">Friends</h4>
			<div class="card p-2">
				{{-- TODO: XlXi: friends --}}
				{{-- TODO: XlXi: Sort friends by online --}}
			</div>
		</div>
	</div>
	
	{{-- Groups pane --}}
	<h4 class="mt-2">Groups</h4>
	<div class="card p-2">
		
	</div>
	
	{{-- Statistics pane --}}
	<h4 class="mt-2">Statistics</h4>
	<div class="card p-3">
		<div class="row text-center">
			<div class="col">
				<p class="fw-bold">Joined</p>
				<p>{{ $user->getJoinDate() }}</p>
			</div>
			<div class="col">
				<p class="fw-bold">Last Seen</p>
				<p>{{ $user->getLastSeen() }}</p>
			</div>
			<div class="col">
				<p class="fw-bold">Visits</p>
				<p>todo</p>
			</div>
			<div class="col">
				<p class="fw-bold">Forum Posts</p>
				<p>todo</p>
			</div>
		</div>
	</div>
</div>
@endsection