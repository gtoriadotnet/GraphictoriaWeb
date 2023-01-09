@extends('layouts.app')

@section('title', 'Transactions')

@section('page-specific')
<script src="{{ mix('js/Transactions.js') }}"></script>
@endsection

@section('content')
<div class="container my-2">
	<h4 class="mb-2">Transactions</h4>
	<div id="vb-transactions">
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<button class="nav-link active disabled" disabled>Summary</button>
			</li>
			<li class="nav-item">
				<button class="nav-link disabled" disabled>My Transactions</button>
			</li>
		</ul>
		<div class="card p-2">
			<x-loader />
		</div>
	</div>
</div>
@endsection