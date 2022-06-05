@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="container my-2">
	<div class="row">
		<div class="col d-flex">
			<h4 class="my-auto">Shop</h4>
		</div>
		<div class="col-lg-5 my-2 my-lg-0 mb-lg-2 d-flex">
			<button class="btn btn-secondary me-2"><i class="fa-solid fa-gear"></i></button>
			<div class="input-group">
				<input type="text" class="form-control d-lg-flex" placeholder="Item name" />
				<button class="btn btn-primary">Search</button>
			</div>
		</div>
	</div>
	<div class="card p-3">
		<div class="graphictoria-catalog-overlay">
			<x-loader />
		</div>
		<div>
			<a class="graphictoria-item-card" href="#">
				<span class="card m-2">
					<img class="img-fluid" src="{{ asset('images/testing/hat.png') }}" />
					<div class="p-2">
						<p>Test hat</p>
						<p class="text-muted">Free</p>
					</div>
				</span>
			</a>
		</div>
	</div>
</div>
@endsection