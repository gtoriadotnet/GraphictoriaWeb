@extends('layouts.app')

@section('title', 'Avatar Editor')

@section('page-specific')
<script src="{{ mix('js/AvatarEditor.js') }}"></script>
@endsection

@section('content')
	<div class="alert alert-yellow virtubrick-alert text-black">
		<p>Todo:</p>
		<ul>
			<li>Section for wearing outfits</li>
		</ul>
	</div>
<div class="container my-2">
	<h4>Avatar Editor</h4>
	<div
		class="row mt-2"
		id="vb-avatar-editor"
		data-asset-thumbnail-2d="{{ route('thumbnails.v1.user', ['id' => Auth::user()->id, 'position' => 'full', 'type' => '2d']) }}"
		data-asset-thumbnail-3d="{{ route('thumbnails.v1.user', ['id' => Auth::user()->id, 'position' => 'full', 'type' => '3d']) }}"
		data-asset-name="{{ Auth::user()->username }}"
		data-renderable3d="1"
	>
		<div class="col-lg-3">
			<div class="card text-center vb-avatar-editor-card">
				<img src="{{ Auth::user()->getImageUrl() }}" class="img-fluid vb-charimg" />
			</div>
			
			<h4 class="mt-3">Colors</h4>
			<div class="card p-4">
				<x-loader />
			</div>
		</div>
		<div class="mt-lg-0 mt-4 col-lg-9">
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<button class="nav-link active disabled" disabled>Wardrobe</button>
				</li>
				<li class="nav-item">
					<button class="nav-link disabled" disabled>Outfits</button>
				</li>
			</ul>
			<div class="card p-2">
				<x-loader />
			</div>
			
			<h4 class="mt-3">Currently Wearing</h4>
			<div class="card p-2">
				<x-loader />
			</div>
		</div>
	</div>
</div>
@endsection