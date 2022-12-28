@extends('layouts.app')

@section('title', $title)

@section('page-specific')
<script src="{{ mix('js/Item.js') }}"></script>
@endsection

@section('quick-admin')
@owner
	<li class="nav-item">
		{{-- TODO: XlXi: Make this use route() --}}
		<a href="{{ url('/admin/grant-asset?id=' . $asset->id) }}" class="nav-link py-0">Grant Asset</a>
	</li>
@endowner
@admin
	<li class="nav-item">
		{{-- TODO: XlXi: Make this use route() --}}
		<a href="{{ url('/admin/rerender-asset?id=' . $asset->id) }}" class="nav-link py-0">Rerender Asset</a>
	</li>
	<li class="nav-item">
		{{-- TODO: XlXi: Make this use route() --}}
		<a href="{{ url('/admin/asset-dependencies?id=' . $asset->id) }}" class="nav-link py-0">Asset Dependencies</a>
	</li>
	<li class="nav-item">
		{{-- TODO: XlXi: Make this use route() --}}
		<a href="{{ url('/admin/endorse-asset?id=' . $asset->id) }}" class="nav-link py-0">Endorse Asset</a>
	</li>
@endadmin
<li class="nav-item">
	{{-- TODO: XlXi: Make this use route() --}}
	<a href="{{ url('/admin/moderate?id=' . $asset->id . '&type=asset') }}" class="nav-link py-0">Moderate Asset</a>
</li>
@endsection

@section('content')
<div class="container mx-auto py-5">
	@if(!$asset->approved)
		<div class="alert alert-danger text-center"><strong>This asset is pending approval.</strong> It will not appear in-game and cannot be voted on or purchased at this time.</div>
	@endif
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
		<div class="card shadow-sm">
			<div class="card-body">
				<div class="d-flex">
					<div class="pe-4">
						<div id="vb-thumbnail"
							class="border position-relative virtubrick-asset-thumbnail"
							data-asset-thumbnail-2d="{{ $asset->getThumbnail() }}"
							data-asset-thumbnail-3d="{{ route('thumbnails.v1.asset', ['id' => $asset->id, 'type' => '3d']) }}"
							data-asset-name="{{ $asset->name }}"
							data-asset-id="{{ $asset->id }}"
							@if($asset->isWearable() && Auth::check())
								data-wearable="true"
							@endif
							@if($asset->canRender3D())
								data-renderable3d="true"
							@endif
						>
							<img src="{{ $asset->getThumbnail() }}" alt="{{ $asset->name }}" class="img-fluid" />
						</div>
					</div>
					<div class="flex-fill">
						<h3 class="mb-0">{{ $asset->name }}</h3>
						<p>By <a class="text-decoration-none fw-normal" href="{{ $asset->user->getProfileUrl() }}">{{ $asset->user->username }}</a></p>
						<hr />
						{{-- TODO: XlXi: limiteds/trading --}}
						<div class="row mt-2">
							<div class="col-3 fw-bold">
								<p>Price</p>
							</div>
							<div class="col-9 d-flex">
								@if( $asset->onSale )
									<h4 class="my-auto virtubrick-tokens">
										{{ number_format($asset->priceInTokens) }}
									</h4>
								@endif
								@auth
									<div id="vb-purchase-button" class="ms-auto">
										<button class="px-5 btn btn-lg btn-success" disabled>Buy</button>
									</div>
								@endauth
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-3 fw-bold">
								<p>Type</p>
							</div>
							<div class="col-9">
								<p>{{ $asset->typeString() }}</p>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-3 fw-bold">
								<p>Created</p>
							</div>
							<div class="col-9">
								<p>{{ $asset->getCreated() }}</p>
							</div>
						</div>
						@if( $asset->getUpdated() != $asset->getCreated() )
							<div class="row mt-2">
								<div class="col-3 fw-bold">
									<p>Updated</p>
								</div>
								<div class="col-9">
									<p>{{ $asset->getUpdated() }}</p>
								</div>
							</div>
						@endif
						<div class="row mt-2">
							<div class="col-3 fw-bold">
								<p>Description</p>
							</div>
							<div class="col-9">
								@if ( $asset->description )
									<p>{{ $asset->description }}</p>
								@else
									<p class="text-muted">This item has no description.</p>
								@endif
							</div>
						</div>
					</div>
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