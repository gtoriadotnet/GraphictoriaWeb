@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="container-lg my-2">
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
	<div class="row">
		<div class="col-md-2">
			<div class="graphictoria-shop-categories border-2 border-end py-1">
				<h5>Category</h5>
				<ul class="list-unstyled ps-0">
					<li class="mb-1">
						<a class="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-test-collapse" aria-expanded="true" href="#">Test</a>
						<div class="collapse show" id="shop-test-collapse">
							<ul class="btn-toggle-nav list-unstyled fw-normal small">
								<li><a href="#" class="text-decoration-none ms-2">Test</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Test</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Test</a></li>
							</ul>
						</div>
					</li>
					<li class="mb-1">
						<a class="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-test2-collapse" aria-expanded="false" href="#">Test 2</a>
						<div class="collapse" id="shop-test2-collapse">
							<ul class="btn-toggle-nav list-unstyled fw-normal small">
								<li><a href="#" class="text-decoration-none ms-2">Test</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Test</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Test</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-10">
			<div class="card p-3">
				<div class="graphictoria-shop-overlay">
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
	</div>
</div>
@endsection