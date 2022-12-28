@extends('layouts.app')

@section('extra-headers')
<style>
	.vb-admin-navtitle {
		font-weight: 700!important; /* fw-bold class */
		
		padding-bottom: 0.5rem!important; /* py-2 class */
		padding-top: 0.5rem!important; /* py-2 class */
		
		padding-left: 1rem!important; /* ps-3 class */
	}
	
	.vb-admin-card:not(:last-child) {
		margin-bottom: 1rem!important;
	}
	
	/* Light Mode Specific */
	html.vbrick-light .vb-admin-navtitle {
		background: #00000005;
	}
	
	html.vbrick-light .vb-admin-navtitle {
		border-bottom: 1px solid #00000020;
	}
	
	html.vbrick-light .vb-admin-navtitle:not(:first-of-type) {
		border-top: 1px solid #00000020;
	}
	
	html.vbrick-light .vb-admin-nav > li > .nav-link:hover {
		background: #00000010;
	}
	
	/* Dark Mode Specific */
	html.vbrick-dark .vb-admin-navtitle {
		background: #ffffff05;
	}
	
	html.vbrick-dark .vb-admin-navtitle {
		border-bottom: 1px solid #ffffff20;
	}
	
	html.vbrick-dark .vb-admin-navtitle:not(:first-of-type) {
		border-top: 1px solid #ffffff20;
	}
	
	html.vbrick-dark .vb-admin-nav > li > .nav-link:hover {
		background: #ffffff10;
	}
</style>

@stack('extra-headers')
@endsection

@section('content')
<div class="row mx-1 my-2">
	<div class="col-md-4 col-lg-3 col-xxl-2">
		@php
			$adminLayout = simplexml_load_string(Storage::get('layouts/Admin.xml'));
		@endphp
		
		@foreach($adminLayout->list as $list)
		<x-admin.nav-list-card name="{{ $list['name'] }}" color="{{ $list['color'] }}" roleset="{{ $list['roleset'] }}">
			@foreach($list->category as $category)
				<x-admin.nav-list-title name="{{ $category['name'] }}">
					@foreach($category->link as $link)
						<x-admin.nav-list-link name="{{ $link['name'] }}" route="{{ $link['route'] }}" parameters="{{ $link['parameters'] }}" />
					@endforeach
				</x-admin.nav-list-title>
			@endforeach
		</x-admin.nav-list-card>
		@endforeach
	</div>
	<div class="col-md-8 col-lg-9 col-xxl-10 mt-2 mt-md-0">
		@stack('content')
	</div>
</div>
@endsection