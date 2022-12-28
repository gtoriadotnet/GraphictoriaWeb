@extends('layouts.admin')

@push('content')
<div class="container">
	<div class="d-flex">
		<h4 class="mt-1">Arbiter Management</h4>
		<div class="ms-auto d-flex">
			<a class="ms-auto btn btn-primary me-1" href="#"><i class="fa-solid fa-rotate"></i></a>
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
	<div class="card p-3 mt-1" id="vb-arbiter-jobs" style="min-height:80px;">
		<div class="virtubrick-shop-overlay">
			<x-loader />
		</div>
	</div>
</div>
@endpush