@extends('layouts.app', ['title' => 'Javascript'])

@section('content')
	<div class="text-center m-auto container">
		<h2>Uh oh!</h2>
		<h5>Your browser doesn't seem to support JavaScript! Please upgrade your browser to use Graphictoria.</h5>
		<hr class="mx-auto" width="20%"/>
		<h4>Javascript Compatible Browsers:</h4>
		<ul class="list-unstyled">
			<li><a href="https://www.google.com/chrome/">Google Chrome</a></li>
			<li><a href="https://www.mozilla.org/en-US/firefox/new/">Mozilla Firefox</a></li>
			<li><a href="https://www.microsoft.com/en-us/edge">Microsoft Edge</a></li>
			<li><a href="https://brave.com/download/">Brave</a></li>
			<li><a href="https://www.opera.com/gx">Opera</a></li>
		</ul>
	</div>
@endsection
