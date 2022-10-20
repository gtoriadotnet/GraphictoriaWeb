@extends('layouts.app')

@section('title', 'Settings')

@section('page-specific')
@endsection

@section('content')
<div id="gt-settings-main" class="container mx-auto my-2">
	<h4 class="my-auto">Settings</h4>
	
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<button class="nav-link active">Account</button>
		</li>
		<li class="nav-item">
			<button class="nav-link">Security</button>
		</li>
		<li class="nav-item">
			<button class="nav-link">Privacy</button>
		</li>
		<li class="nav-item">
			<button class="nav-link">Appearance</button>
		</li>
	</ul>
	<div class="card p-2">
		<h5>Account Settings</h5>
		<div class="row">
			<div class="col-3 fw-bold">
				<p>Username</p>
			</div>
			<div class="col-9">
				<p>XlXi</p>
			</div>
		</div>
	</div>
</div>
@endsection