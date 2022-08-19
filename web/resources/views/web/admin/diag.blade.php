@extends('layouts.app')

@section('extra-headers')
<style>
	.graphictoria-graph-card {
		width: 300px!important;
		height: 200px!important;
		margin: 5px;
	}
</style>
@endsection

@section('content')
<div class="container my-2">
	<h4>Arbiter Diag <span class="text-muted">(RccServiceMonitor)</span></h4>
	<div class="d-flex flex-wrap">
		<x-minicard class="graphictoria-graph-card">
			<x-slot name="title">
				Active Jobs
			</x-slot>
			<x-slot name="body">
				<div class="graphictoria-shop-overlay">
					<x-loader />
				</div>
			</x-slot>
		</x-minicard>
	</div>
	<h4 class="mt-2">Soap Exceptions</h4>
	<div class="card p-3" id="gt-soap-exceptions" style="min-height:80px;">
		<div class="graphictoria-shop-overlay">
			<x-loader />
		</div>
	</div>
</div>
@endsection