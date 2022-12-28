@extends('layouts.app')

@section('title', 'Test Site Info')

@section('content')
<div class="virtubrick-smaller-page my-4">
	<div class="text-center">
		<img src="{{ asset('images/logo-256x256.png') }}" class="img-fluid" />
		<h2 class="my-auto">{{ config('app.name') }} Test Environment</h2>
	</div>
	<div class="mx-5 mt-4">
		<h4 class="my-2">Site Information</h4>
		<div class="card p-3">
			<p>The {{ config('app.name') }} testing site allows us to get detailed feedback on {{ config('app.name') }} operation. This site allows us to test many things, which includes and is not limited to the following:</p>
			<ul>
				<li>Diagnose and resolve possible issues in our infrastructure.</li>
				<li>Gather information on feature usage.</li>
				<li>Stress test {{ config('app.name') }} and apply our research to improve it's stability.</li>
				<li>Test and implement our changes in real-time to help our rapid development.</li>
				<li>Fine-tune parts of the website/game client.</li>
				<li>Provide our team of quality assurance members to do their thing.</li>
				<li>Allow for staff members to explore how our administration system works so they can use it in the most optimal way.</li>
			</ul>
			<hr />
			<p>You can help us test {{ config('app.name') }} by using this site like you would normally. However, it is to be noted that the data on this site may be wiped at any time, without warning. Things breaking is also to be expected, as some features of the site may still be in the testing phase.</p>
			<p class="mt-3">Thanks,<br/>XlXi</p>
		</div>
	</div>
</div>
@endsection