@extends('layouts.admin')

@section('title', 'Uploaded Assets')

@push('content')
	<div class="container-md">
		<x-admin.navigation.asset-uploader />
		<h4>Uploaded Assets</h4>
		<div class="card">
			@if(isset($uploads) && count($uploads) > 0)
				<table class="table virtubrick-table">
					<thead>
						<tr>
							<th scope="col">Asset</th>
							<th scope="col">Type</th>
							<th scope="col">Uploader</th>
							<th scope="col">Created</th>
						</tr>
					</thead>
					<tbody>
						@foreach($uploads as $upload)
							@php
								$asset = $upload->asset;
							@endphp
							<tr class="align-middle">
								<th scope="col"><a href="{{ $asset->getShopUrl() }}" class="text-decoration-none">{{ $asset->name }}</th>
								<th scope="col">{{ $asset->typeString() }}</th>
								<th scope="col">
									<a href="{{ route('admin.useradmin', ['ID' => $asset->user->id]) }}" class="text-decoration-none">
										<x-user-circle :user="$asset->user" :size=40 />
									</a>
								</th>
								<th scope="col">{{ $upload->created_at->isoFormat('l LT') }}</th>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $uploads->links('pagination.virtubrick') }}
			@else
				<p class="text-muted p-3">No assets found.</p>
			@endif
		</div>
	</div>
@endpush