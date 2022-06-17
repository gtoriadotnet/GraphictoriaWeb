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
			<div class="graphictoria-shop-categories">
				<h5>Category</h5>
				<a href="#" class="text-decoration-none">All Items</a>
				<ul class="list-unstyled ps-0">
					<li class="mb-1">
						<a class="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-clothing-collapse" aria-expanded="true" href="#">Clothing</a>
						<div class="collapse show" id="shop-clothing-collapse">
							<ul class="btn-toggle-nav list-unstyled fw-normal small">
								<li><a href="#" class="text-decoration-none ms-2">All Clothing</a></li>
								<li><a href="#" class="fw-bold text-decoration-none ms-2">Hats</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Shirts</a></li>
								<li><a href="#" class="text-decoration-none ms-2">T-Shirts</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Pants</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Packages</a></li>
							</ul>
						</div>
					</li>
					<li class="mb-1">
						<a class="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-bodyparts-collapse" aria-expanded="false" href="#">Body Parts</a>
						<div class="collapse" id="shop-bodyparts-collapse">
							<ul class="btn-toggle-nav list-unstyled fw-normal small">
								<li><a href="#" class="text-decoration-none ms-2">All Body Parts</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Heads</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Faces</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Packages</a></li>
							</ul>
						</div>
					</li>
					<li class="mb-1">
						<a class="text-decoration-none fw-normal align-items-center graphictoria-list-dropdown" data-bs-toggle="collapse" data-bs-target="#shop-gear-collapse" aria-expanded="false" href="#">Gear</a>
						<div class="collapse" id="shop-gear-collapse">
							<ul class="btn-toggle-nav list-unstyled fw-normal small">
								<li><a href="#" class="text-decoration-none ms-2">All Gear</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Building</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Explosive</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Melee</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Musical</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Navigation</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Power Up</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Ranged</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Social</a></li>
								<li><a href="#" class="text-decoration-none ms-2">Transport</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-10 d-flex flex-column">
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
			<ul class="list-inline mx-auto mt-3">
				<li class="list-inline-item">
					<button class="btn btn-secondary disabled"><i class="fa-solid fa-angle-left"></i></button>
				</li>
				<li class="list-inline-item graphictoria-paginator">
					<span>Page</span>
					<input type="text" value="1" class="form-control">
					<span>of 20</span>
				</li>
				<li class="list-inline-item">
					<button class="btn btn-secondary"><i class="fa-solid fa-angle-right"></i></button>
				</li>
			</ul>
		</div>
	</div>
</div>
@endsection