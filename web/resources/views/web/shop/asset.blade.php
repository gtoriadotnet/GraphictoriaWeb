@extends('layouts.app')

@section('title', $title)

@section('page-specific')
@endsection

@section('content')
{{-- XlXi: MOVE THESE TO JS --}}
@if(false)
<div class="modal fade" id="purchase-modal" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content text-center">
			<div class="modal-header">
				<h5 class="modal-title">Purchase Item</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body d-flex flex-column">
				<p>Would you like to purchase the {{ $asset->typeString() }} "<strong>{{ $asset->name }}</strong>" from {{ $asset->user->username }} for <strong style="color:#e59800!important;font-weight:bold"><img src="{{ asset('images/symbols/token.svg') }}" height="16" width="16" class="img-fluid" style="margin-top:-1px" />{{ number_format($asset->priceInTokens) }}</strong>?</p>
				<img src={{ asset('images/testing/hat.png') }} width="240" height="240" alt="{{ $asset->name }}" class="mx-auto my-2 img-fluid" />
			</div>
			<div class="modal-footer flex-column">
				<div class="mx-auto">
					<button class="btn btn-success" data-bs-dismiss="modal">Purchase</button>
					<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
				<p class="text-muted pt-1">You will have <strong style="color:#e59800!important;font-weight:bold"><img src="{{ asset('images/symbols/token.svg') }}" height="16" width="16" class="img-fluid" style="margin-top:-1px" />{{ max(0, number_format(Auth::user()->tokens - $asset->priceInTokens)) }}</strong> after this purchase.</p>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="purchase-modal" aria-hidden="true" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content text-center">
			<div class="modal-header">
				<h5 class="modal-title">Insufficient Funds</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>You don't have enough tokens to buy this item.</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" data-bs-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>
@endif

<div class="container mx-auto py-5">
	@if(!$asset->approved)
		<div class="alert alert-danger text-center"><strong>This asset is pending approval.</strong> It will not appear in-game and cannot be voted on or purchased at this time.</div>
	@endif
	<div class="graphictoria-item-page">
		<div class="card shadow-sm">
			<div class="card-body">
				<div class="d-flex">
					<div class="pe-4">
						<img src={{ asset('images/testing/hat.png') }} alt="{{ $asset->name }}" class="border img-fluid" />
					</div>
					<div class="flex-fill">
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
										<img src="{{ asset('images/symbols/token.svg') }}" height="32" width="32" class="img-fluid me-1" style="margin-top:-1px" />{{ number_format($asset->priceInTokens) }}</h4>
									</h4>
								@endif
								@auth
									@php
										$buttonText = 'Buy';
										$buttonClass = 'success';
										
										// TODO: XlXi: Owned items
										if(!$asset->onSale) {
											$buttonText = 'Offsale';
											$buttonClass = 'secondary';
										}
									@endphp
									<button id="purchase-button" class="ms-auto px-5 btn btn-lg btn-{{ $buttonClass }}" disabled>{{ $buttonText }}</button>
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
		<div id="gt-comments"></div>
	</div>
</div>
@endsection