@extends('layouts.app')

@section('title', $title)

@section('page-specific')
@endsection

@section('content')
<div class="container pt-5">
	@if(!$asset->approved)
		<div class="alert alert-danger text-center"><strong>This asset is pending approval.</strong> It will not appear in-game and cannot be voted on or purchased at this time.</div>
	@endif
	<div class="card shadow-sm">
		<div class="card-body">
			<div class="row">
				<div class="col-5">
					<img src={{ asset('images/testing/hat.png') }} alt="{{ $asset->name }}" class="border img-fluid" />
				</div>
				<div class="col-7">
					<h3 class="mb-0">{{ $asset->name }}</h3>
					{{-- TODO: XlXi: url to user's profile --}}
					<p>By {{ $asset->user->username }}</p>
					<hr />
					{{-- TODO: XlXi: limiteds/trading --}}
					<div class="row mt-2">
						<div class="col-3 fw-bold">
							<p>Price</p>
						</div>
						<div class="col-9 d-flex">
							@if( $asset->onSale )
								<h4 class="my-auto" style="color:#e59800!important;font-weight:bold">
									<img src="{{ asset('images/symbols/token.svg') }}" height="32" width="32" class="img-fluid me-1" style="margin-top:-1px" />{{ \App\Helpers\NumberHelper::Abbreviate(Auth::user()->tokens) }}</h4>
								</h4>
							@endif
							@php
								$buttonText = 'Buy';
								$buttonClass = 'success';
								
								// TODO: XlXi: Owned items
								if(!$asset->onSale) {
									$buttonText = 'Offsale';
									$buttonClass = 'secondary';
								}
							@endphp
							<button class="ms-auto px-5 btn btn-lg btn-{{ $buttonClass }}" {{ !$asset->onSale ? 'disabled' : '' }}>{{ $buttonText }}</button>
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
</div>
@endsection