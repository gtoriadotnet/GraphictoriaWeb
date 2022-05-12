@extends('layouts.app')

@section('content')
<div class="graphictoria-home">
	<div class="container graphictoria-center-vh my-auto text-center text-white">
		<div class="mb-4 graphictoria-home-shadow">
			<h1 class="graphictoria-homepage-header">Graphictoria</h1>
			{{-- TODO: make the user count automatic via a model --}}
			<h5 class="mb-0">Graphictoria aims to revive the classic Roblox experience. Join <b>10k+</b> other users and relive your childhood!</h5>
			<p class="graphictoria-homepage-fine-print fst-italic">* Graphictoria is not affiliated with, endorsed by, or sponsored by Roblox Corporation.</p>
		</div>
		<a href="{{ route('auth.register.index') }}" class="btn btn-success">Create your account<i class="ps-2 graphictoria-small-aligned-text fas fa-chevron-right"></i></a>
	</div>
</div>
<div class="graphictoria-home-about my-1">
	<div class="col-md-10 d-flex h-100">
		<div class="graphictoria-home-about-card text-center m-auto">
			<h1 class="fw-bold">So what is Graphictoria?</h1>
			<h4>Ever wanted to experience or revisit classic Roblox? Graphictoria provides the platform for anyone and everyone looking to relive the classic Roblox experience. Play with friends in an immersive 3D environment, or create your own game. Your imagination is the only limit.</h4>
		</div>
	</div>
</div>
<div class="container text-center my-5">
	<h1 class="mb-5 fw-bold">Social Links</h1>
	<div class="row mb-5">
		<x-socialcard title="YouTube" description="Subscribe to our YouTube channel, where we upload trailers for future events and Graphictoria gameplay videos." link="https://www.youtube.com/graphictoria?sub_confirmation=1" />
		<x-socialcard title="Twitter" description="Follow us on Twitter. Here you can recieve important updates about Graphictoria and receive announcements for events, potential downtime, status reports, etc." link="https://twitter.com/intent/user?screen_name=gtoriadotnet" />
		<x-socialcard title="Discord" description="Join our Discord server. This is the place where you can engage with the rest of our community, or just hang out with friends." link="https://www.discord.gg/jBRHAyp" />
	</div>
</div>
@endsection
