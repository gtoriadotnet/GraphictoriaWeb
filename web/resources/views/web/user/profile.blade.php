@extends('layouts.app')

@section('title', $title)

@section('page-specific')
@endsection

@section('quick-admin')
<li class="nav-item">
	{{-- TODO: XlXi: Make this use route() --}}
	<a href="{{ url('/admin') }}" class="nav-link py-0">User Admin</a>
</li>
<li class="nav-item">
	{{-- TODO: XlXi: Make this use route() --}}
	<a href="{{ url('/admin') }}" class="nav-link py-0">Moderate User</a>
</li>
@endsection

@section('content')
<style>
.graphictoria-user-profile-buttons {
	display: flex;
	justify-content: center;
}

.graphictoria-user-profile-buttons > .btn:not(:last-child) {
	/* me-1 */
	margin-right: 0.25rem!important;
}
</style>

<div class="container-lg my-2">
	<div class="graphictoria-smaller-page">
		<div class="row">
			<div class="col-md-6">
				<div class="card p-2 text-center">
					<h5 class="mb-0"><span class="{{ $user->getOnlineClass() }}">●</span> {{ $user->username }}</h5>
					@if(false)
						{{-- TODO: XlXi: this lol --}}
						<p class="text-success">[ Playing: Among Us ]</p>
					@endif
					<img src="{{ asset('/images/testing/avatar.png') }}" width="300px" height="300px" class="img-fluid mx-auto gt-charimg" />
					<p class="text-muted pb-2">TODO: user description</p>
					@auth
						<div class="graphictoria-user-profile-buttons">
							<button class="btn btn-secondary">Add Friend</button>
							<button class="btn btn-secondary">Send Message</button>
							<button class="btn btn-secondary">Block</button>
						</div>
					@endauth
					<hr class="mb-1" />
					<div class="row text-center">
						<div class="col-4">
							<p class="fw-bold">Joined</p>
							<p>{{ $user->getJoinDate() }}</p>
						</div>
						<div class="col-4">
							<p class="fw-bold">Last Seen</p>
							<p>{{ $user->getLastSeen() }}</p>
						</div>
						<div class="col-4">
							<p class="fw-bold">Visits</p>
							<p>0</p>
						</div>
					</div>
					<hr class="mt-1 mb-1" />
					@auth
						<a href="https://www.gtoria.local/todo123" class="ms-auto text-decoration-none link-danger">Report <i class="fa-solid fa-circle-exclamation"></i></a>
					@endauth
				</div>
				
				<x-MiniCard class="mt-3 d-flex">
					<x-slot name="title">
						Official Badges
					</x-slot>
					<x-slot name="body">
						<p class="text-muted">todo</p>
					</x-slot>
				</x-MiniCard>
				
				<x-MiniCard class="mt-3 d-flex">
					<x-slot name="title">
						Badges
					</x-slot>
					<x-slot name="body">
						<p class="text-muted">todo</p>
					</x-slot>
				</x-MiniCard>
			</div>
			<div class="col-md-6">
				<x-MiniCard class="mt-3 mt-md-0 d-flex">
					<x-slot name="title">
						Games
					</x-slot>
					<x-slot name="body">
						<p class="text-muted">todo</p>
					</x-slot>
				</x-MiniCard>
				
				<x-MiniCard class="mt-3 d-flex">
					<x-slot name="title">
						Favorites
					</x-slot>
					<x-slot name="body">
						<p class="text-muted">todo</p>
					</x-slot>
				</x-MiniCard>
				
				<x-MiniCard class="mt-3 d-flex">
					<x-slot name="title">
						Friends
					</x-slot>
					<x-slot name="body">
						<p class="text-muted">todo</p>
					</x-slot>
				</x-MiniCard>
			</div>
		</div>
		<x-MiniCard class="mt-3 d-flex">
			<x-slot name="title">
				Groups
			</x-slot>
			<x-slot name="body">
				<p class="text-muted">todo</p>
			</x-slot>
		</x-MiniCard>
	</div>
</div>
@endsection