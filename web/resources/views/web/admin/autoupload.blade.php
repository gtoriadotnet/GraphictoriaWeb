@extends('layouts.admin')

@section('title', 'Auto Uploader')

@push('content')
	<div class="container-md">
		<h4>Auto Uploader</h4>
		<div class="card p-3">
			<label for="vb-rbx-asset" class="form-label">Roblox Asset ID</label>
			<div class="input-group mb-3">
				<input
					type="text"
					class="form-control"
					placeholder="Roblox Asset ID Here"
					aria-label="Roblox Asset ID Here"
					name="rbx-asset" id="vb-rbx-asset"
					aria-describedby="vb-rbx-asset"
				>
				<button type="submit" class="btn btn-primary" type="button" name="rbx-asset-button" id="vb-rbx-asset-btn">Search</button>
			</div>
		</div>
	</div>
@endpush