@extends('layouts.app')

@section('title', 'Avatar Editor')

@section('content')
<div class="container my-2">
	<h4>Avatar Editor</h4>
	<div class="row mt-2">
		<div class="col-3">
			<div class="card text-center" id="vb-character-thumbnail">
				<img src="{{ Auth::user()->getImageUrl() }}" class="img-fluid vb-charimg" />
			</div>
			
			<h4 class="mt-3">Colors</h4>
			<div class="card p-2" id="vb-character-colors">
				<x-loader />
			</div>
		</div>
		<div class="col-9">
			<ul class="nav nav-tabs" id="vb-character-tabs">
				<li class="nav-item">
					<button class="nav-link active disabled" disabled>Wardrobe</button>
				</li>
				<li class="nav-item">
					<button class="nav-link disabled" disabled>Outfits</button>
				</li>
			</ul>
			<div class="card p-2" id="vb-character-editor">
				<x-loader />
			</div>
			
			<h4 class="mt-3">Currently Wearing</h4>
			<div class="card p-2" id="vb-character-wearing">
				<x-loader />
			</div>
		</div>
	</div>
	<p>Todo:</p>
	<ul>
		<li>Character Preview</li>
		<li>3d Character Preview</li>
		<li>Redraw button</li>
		<li>Section for changing body colors</li>
		<li>Section for wearing items</li>
		<li>Section for taking off worn items</li>
	</ul>
</div>
@endsection