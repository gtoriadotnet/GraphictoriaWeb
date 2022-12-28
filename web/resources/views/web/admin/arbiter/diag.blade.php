@extends('layouts.admin')

@push('extra-headers')
<style>
	.virtubrick-graph-card {
		width: 300px!important;
		height: 200px!important;
		margin: 5px;
	}
</style>
@endpush

@push('content')
<div class="container">
	<div class="d-flex">
		<h4 class="mt-1">Arbiter Diag <span class="text-muted">(RccServiceMonitor)</span></h4>
		<div class="ms-auto d-flex">
			<div class="dropdown">
				<button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					Games
				</button>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item" href="#">Games</a></li>
					<li><a class="dropdown-item" href="#">Thumbnail</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="d-flex flex-wrap">
		<x-minicard class="virtubrick-graph-card">
			<x-slot name="title">
				Active Jobs
			</x-slot>
			<x-slot name="body">
				<div class="virtubrick-shop-overlay">
					<x-loader />
				</div>
			</x-slot>
		</x-minicard>
	</div>
	<h4 class="mt-2">Soap Exceptions</h4>
	<div class="card p-3" id="vb-soap-exceptions" style="min-height:80px;">
		<div class="virtubrick-shop-overlay">
			<x-loader />
		</div>
	</div>
</div>
@endpush