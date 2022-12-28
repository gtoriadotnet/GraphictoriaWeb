@extends('layouts.app')

@section('title', $title)

@section('page-specific')
<script src="{{ mix('js/Place.js') }}"></script>
@endsection

@section('quick-admin')
<li class="nav-item">
	{{-- TODO: XlXi: Make this use route() --}}
	<a href="{{ url('/admin/moderate?id=' . $asset->id . '&type=asset') }}" class="nav-link py-0">Moderate Game</a>
</li>
@endsection

@section('content')
<div class="container mx-auto py-5">
	<div id="vb-item" class="virtubrick-smaller-page"
		@auth
			data-asset-id="{{ $asset->id }}"
			data-asset-name="{{ $asset->name }}"
			data-asset-creator="{{ $asset->user->username }}"
			data-asset-type="{{ $asset->typeString() }}"
			data-asset-on-sale="{{ $asset->onSale }}"
			@if ($asset->onSale)
				data-asset-price="{{ $asset->priceInTokens }}"
				data-user-currency="{{ Auth::user()->tokens }}"
				data-can-afford="{{ $asset->priceInTokens <= Auth::user()->tokens }}"
			@endif
		@endauth
	>
		@if($asset->universe->startPlaceId != $asset->id)
			@php
				$assetUniverseLink = route('games.asset', ['asset' => $asset->universe->starterPlace->id, 'assetName' => Str::slug($asset->universe->starterPlace->name, '-')]);
			@endphp
			<div class="alert alert-primary p-2 mb-2 d-flex">
				<div class="my-auto">
					<i class="fa-solid fa-layer-group"></i>
					This place is a part of <a class="text-decoration-none fw-bold alert-link" href="{{ $assetUniverseLink }}">{{ $asset->universe->name }}</a>.
				</div>
				<a class="btn btn-light btn-sm ms-auto" href="{{ $assetUniverseLink }}">Play</a>
			</div>
		@endif
		<div class="card shadow-sm">
			<div class="card-body">
				<div class="d-flex">
					<div class="pe-4">
						<div id="vb-thumbnail"
							class="border position-relative virtubrick-game-thumbnail"
							data-asset-thumbnail-2d="{{ $asset->getThumbnail() }}"
							data-asset-name="{{ $asset->name }}"
							data-asset-id="{{ $asset->id }}"
						>
							<img src="{{ $asset->getThumbnail() }}" alt="{{ $asset->name }}" width="640" height="360" />
						</div>
					</div>
					<div class="flex-fill d-flex flex-column">
						<h3 class="mb-0">{{ $asset->universe->name }}</h3>
						<p>By <a class="text-decoration-none fw-normal" href="{{ $asset->user->getProfileUrl() }}">{{ $asset->user->username }}</a></p>
						<hr />
						{{-- TODO: XlXi: Request playability from api --}}
						{{-- TODO: XlXi: Get like/dislike ratio from a voting api --}}
						<div id="vb-place-buttons"
							class="d-flex flex-column mt-auto"
							data-place-id="{{ $asset->id }}"
						>
							<x-loader />
						</div>
						@if(false)
						<button class="btn btn-lg btn-success mt-auto fs-3" disabled><i class="fa-solid fa-play"></i></button>
						<div class="d-flex mt-2">
							<button class="text-decoration-none fw-normal p-1 btn-favorite">
								<span class="fs-5">
									<i class="fa-regular fa-star"></i> 0
								</span>
							</button>
							<div class="mx-3 flex-fill d-flex">
								<button class="text-decoration-none fw-normal p-1 btn-upvote">
									<span class="fs-5">
										<i class="fa-regular fa-thumbs-up"></i> 0
									</span>
								</button>
								<div class="my-auto flex-fill virtubrick-vote-bar rounded-1 border border-light position-relative flex-fill bg-light">
									<div class="rounded-1 position-absolute bg-dark" style="width: 0%; height: 8px;"></div>
								</div>
								<button class="text-decoration-none fw-normal p-1 btn-downvote">
									<span class="fs-5">
										<i class="fa-regular fa-thumbs-down"></i> 0
									</span>
								</button>
							</div>
						</div>
						@endif
					</div>
				</div>
				<h4 class="mt-3 mb-0">Description</h4>
				@if ( $asset->description )
					<p>{{ $asset->description }}</p>
				@else
					<p class="text-muted">This game has no description.</p>
				@endif
				<hr />
				<div class="row text-center">
					<div class="col">
						<p class="fw-bold">Playing</p>
						<p>todo</p>
					</div>
					<div class="col">
						<p class="fw-bold">Visits</p>
						<p>todo</p>
					</div>
					<div class="col">
						<p class="fw-bold">Created</p>
						<p>test</p>
					</div>
					<div class="col">
						<p class="fw-bold">Updated</p>
						<p>test</p>
					</div>
					<div class="col">
						<p class="fw-bold">Max Players</p>
						<p>test</p>
					</div>
					<div class="col">
						<p class="fw-bold">Genre</p>
						<p>test</p>
					</div>
					<div class="col">
						<p class="fw-bold">Allowed Gear</p>
						<p>test</p>
					</div>
				</div>
				<hr class="mb-2" />
				<div class="d-flex">
					{{-- TODO: XlXi: convert this to a route --}}
					<a href="https://www.virtubrick.local/report/asset/notfinishedtodo" target="_blank" class="text-decoration-none link-danger ms-auto">Report <i class="fa-solid fa-circle-exclamation"></i></a>
				</div>
			</div>
		</div>
		<div id="vb-comments"
			data-can-comment="{{ intval(Auth::check()) }}"
			data-asset-id="{{ $asset->id }}"
		></div>
	</div>
</div>
@endsection