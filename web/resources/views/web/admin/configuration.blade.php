@extends('layouts.admin')

@section('title', 'Site Configuration')

@section('page-specific')
<style>
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
<script src="data:text/javascript;base64,{{ base64_encode(File::get(public_path('js/adm/SiteConfiguration.js'))) }}"></script>
@endsection

@push('content')
	<div class="container-md">
		<h4>Site Configuration</h4>
		<div class="card p-2">
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