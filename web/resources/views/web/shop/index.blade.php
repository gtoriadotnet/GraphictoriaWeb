@extends('layouts.app')

@section('title', 'Shop')

@section('page-specific')
<script src="{{ mix('js/Shop.js') }}"></script>
@endsection

@section('content')
<div id="vb-shop-main" class="container-lg my-2 d-flex flex-column">
	<h4 class="my-auto">Shop</h4>
	<x-loader />
</div>
@endsection