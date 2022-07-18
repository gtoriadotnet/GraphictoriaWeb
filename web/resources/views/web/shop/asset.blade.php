@extends('layouts.app')

@section('title', $title)

@section('page-specific')
@endsection

@section('content')
<div class="container graphictoria-center-vh">
	@if(!$asset->approved)
		<div class="alert alert-danger text-center"><strong>This asset is pending approval.</strong> It will not appear in-game and cannot be voted on or purchased at this time.</div>
	@endif
	<div class="card shadow-sm">
		<div class="card-body">
			<div class="row">
				<div class="col-6">
				</div>
				<div class="col-6">
					<h4 class="mb-0">{{ $asset->name }}</h4>
					{{-- TODO: XlXi: url to user's profile --}}
					<p>By: {{ $asset->user->username }}<p>
					<br />
					<p>Type: {{ $asset->typeString() }}</p>
					<p>Description:</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection