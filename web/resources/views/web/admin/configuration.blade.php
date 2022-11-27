@extends('layouts.admin')

@section('title', 'Site Configuration')

@section('page-specific')
<style>
	.gt-conf-row > .row {
		padding: 0.5rem!important;
		margin: 0;
	}
	
	.gt-conf-row:nth-of-type(even)>* {
		background-color: #0000000d;
	}
	
	.gt-conf-row > .row > div:not(:last-child) {
		border-right: 1px solid #00000020;
	}
	
	/* old stuff */
	.gt-small-row {
		width: 0;
		text-align: center;
	}
	
	.gt-masked-text {
		filter: blur(3px);
	}
	
	.gt-masked-text-shown {
		color: red;
	}
</style>

<!-- Secure Page JS -->
<script src="{{ mix('js/adm/SiteConfiguration.js') }}"></script>
@endsection

@push('content')
	<div class="container-md">
		<h4>Site Configuration</h4>
		<div class="card">
			<div class="gt-conf-row">
				<div class="row">
					<div class="col-3">
						<p>Name</p>
					</div>
					<div class="col-6">
						<p>Value</p>
					</div>
					<div class="col-2">
						<p>Last Modified</p>
					</div>
					<div class="col-1">
						<button class="btn btn-sm btn-primary mx-auto" disabled>Edit</button>
					</div>
				</div>
			</div>
			<table class="table table-sm table-bordered table-striped mb-2">
				<thead>
					<tr>
						<th>ID</th>
						<th>Actions</th>
						<th>Name</th>
						<th>Value</th>
						<th>Created</th>
						<th>Modified</th>
					</tr>
				</thead>
				<tbody id="gt-config-values">
					@foreach($values as $conf)
						@php
							$confValue = ($conf->masked ? $conf->getJumbledValue() : $conf->getTruncatedValue())
						@endphp
						<tr
							data-id="{{ $conf->id }}"
							data-name="{{ $conf->name }}"
							data-is-masked="{{ $conf->masked }}"
							data-value="{{ $confValue }}"
							data-created="{{ $conf->getCreated() }}"
							data-updated="{{ $conf->getUpdated() }}"
						>
							<th class="gt-small-row">{{ $conf->id }}</th>
							<td class="gt-small-row"><button class="btn btn-sm btn-primary" disabled>Edit</button></td>
							<td>{{ $conf->name }}</td>
							<td @class(['d-flex' => $conf->masked])>
								@if($conf->masked)
									<span class="gt-masked-text">{{ $confValue }}</span>
									<button class="ms-auto btn btn-sm btn-danger" disabled>Unmask</button>
								@else
									{{ $confValue }}
								@endif
							</td>
							<td>{{ $conf->getCreated() }}</td>
							<td>{{ $conf->getUpdated() }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endpush