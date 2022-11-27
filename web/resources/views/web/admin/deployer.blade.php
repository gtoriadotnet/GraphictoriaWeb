@extends('layouts.admin')

@section('title', 'App Deployer')

@section('page-specific')
<!-- Secure Page JS -->
<script src="{{ mix('js/adm/AppDeployer.js') }}"></script>
@endsection

@push('content')
<div class="container">
	<div class="alert alert-warning graphictoria-alert graphictoria-error-popup">Ensure the RCC version compatibility security settings and api keys are synced before deploying, else you may completely brick games or api calls.</div>
	<h4 class="mt-1">App Deployer</h4>
	<div class="card p-2" id="gt-deployer">
		<x-loader />
		
		@if(false)
		<h5>Deployment Options</h5>
		<div class="d-block">
			<div class="btn-group mb-1">
				<input type="radio" class="btn-check" name="gt-deployment-type" id="gt-deployment-deploy" autocomplete="off" checked>
				<label class="btn btn-sm btn-outline-primary" for="gt-deployment-deploy">Deploy</label>
				<input type="radio" class="btn-check" name="gt-deployment-type" id="gt-deployment-revert" autocomplete="off">
				<label class="btn btn-sm btn-outline-primary" for="gt-deployment-revert">Revert</label>
			</div>
			<br />
			<button class="btn btn-sm btn-success" disabled>Deploy Client</button>
			<button class="btn btn-sm btn-primary">Deploy Studio</button>
		</div>
		<hr />
		<div class="alert alert-danger graphictoria-alert graphictoria-error-popup">Remove your [deployments/reversions] to change the uploader type.</div>
		<h5>Revert Deployments</h5>
		<div class="card">
			<div class="card-header d-flex">
				<span>Revert Client</span>
				<button class="ms-auto btn-close"></button>
			</div>
			<div class="card-body">
				<h5 class="mb-0">Revert Deployment</h5>
				<p class="text-muted">Select a previous deployment below to roll back the [client/studio] version.</p>
				<select class="form-select mt-2" id="gt-revert-deployment">
					<option selected>None Selected</option>
					<option value="version-abcdefghijk">[Client 1.0.0.2] [11/23/2022] version-abcdefghijk</option>
					<option value="version-bbcdefghijk">[Client 1.0.0.1] [11/22/2022] version-bbcdefghijk</option>
					<option value="version-cbcdefghijk">[Client 1.0.0.0] [11/21/2022] version-cbcdefghijk</option>
				</select>
			</div>
		</div>
		<p class="text-muted">No deployments are selected.</p>
		
		<h5>Staged Deployments</h5>
		<div class="card">
			<div class="card-header d-flex">
				<span>Deploy Client</span>
				<button class="ms-auto btn-close"></button>
			</div>
			<div class="card-body">
				<h5 class="mb-0">Deployment Files</h5>
				<p class="text-muted">Drag-and-Drop the necessary files into the box below.</p>
				<div class="card bg-secondary mt-3 p-3">
					<div>
						{{-- XlXi: Reusing game cards here because they were already exactly what I wanted. --}}
						<div class="graphictoria-item-card graphictoria-game-card">
							<div class="card m-2" data-bs-toggle="tooltip" data-bs-placement="top" title="GraphictoriaLauncherBeta.exe">
								<div class="bg-light d-flex p-3">
									<i class="m-auto fs-1 fa-regular fa-browser"></i>
								</div>
								<div class="p-2">
									<p class="text-truncate">GraphictoriaLauncherBeta.exe</p>
									<button class="btn btn-sm btn-danger mt-1 w-100">Remove</button>
								</div>
							</div>
						</div>
						
						<div class="graphictoria-item-card graphictoria-game-card">
							<div class="card m-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Libraries.zip">
								<div class="bg-light d-flex p-3">
									<i class="m-auto fs-1 fa-regular fa-file-zipper"></i>
								</div>
								<div class="p-2">
									<p class="text-truncate">Libraries.zip</p>
									<button class="btn btn-sm btn-danger mt-1 w-100">Remove</button>
								</div>
							</div>
						</div>
					</div>
					<hr/>
					<h5>Needed Files</h5>
					<div class="small">
						<p class="text-success">Libraries.zip</p>
						<p class="text-danger">GraphictoriaApp.zip</p>
					</div>
					<hr/>
					<h5>Optional Files</h5>
					<div class="small">
						<p class="text-warning">GraphictoriaLauncherBeta 2.exe</p>
						<p class="text-success">GraphictoriaLauncherBeta.exe</p>
					</div>
				</div>
				<h5 class="mb-0 mt-3">Optional Configuration</h5>
				<p class="text-muted mb-3">Only change if you've updated the security settings on the client/rcc. Shutting down game servers will delay deployment by 10 minutes.</p>
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" role="switch" id="gt-shut-down-servers">
					<label class="form-check-label" for="gt-shut-down-servers">Shut down game servers.</label>
				</div>
				<label for="gt-rcc-security-key" class="form-label mt-2">Update RCC Security Key</label>
				<input type="text" id="gt-rcc-security-key" class="form-control" placeholder="New RCC Security Key"/>
				<label for="gt-rcc-security-key" class="form-label mt-2">Update Version Compatibility Salt</label>
				<input type="text" id="gt-rcc-security-key" class="form-control" placeholder="New Version Compatibility Salt"/>
			</div>
		</div>
		<p class="text-muted">No deployments are selected.</p>
		<hr />
		<button class="btn btn-primary">Deploy / Revert Deployment</button>
		@endif
	</div>
</div>
@endpush