@extends('layouts.app')

@section('content')
<div class="virtubrick-home">
	<div class="container virtubrick-center-vh my-auto text-center text-white">
		<div class="mb-4 virtubrick-home-shadow">
			<h1 class="virtubrick-homepage-header">{{ config('app.name') }}</h1>
			{{-- TODO: make the user count automatic via a model --}}
			<h5 class="mb-0">{{ config('app.name') }} aims to revive the classic Roblox experience. Join <b>10k+</b> other users and relive your childhood!</h5>
			<p class="virtubrick-homepage-fine-print fst-italic">* {{ config('app.name') }} is not affiliated with, endorsed by, or sponsored by Roblox Corporation.</p>
		</div>
		<a href="{{ route('auth.register.index') }}" class="btn btn-success">Create your account<i class="ps-2 virtubrick-small-aligned-text fas fa-chevron-right"></i></a>
	</div>
</div>
<div class="virtubrick-home-about my-1">
	<div class="col-md-10 d-flex h-100">
		<div class="virtubrick-home-about-card text-center m-auto">
			<h1 class="fw-bold">So what is {{ config('app.name') }}?</h1>
			<h4>Ever wanted to experience or revisit classic Roblox? {{ config('app.name') }} provides the platform for anyone and everyone looking to relive the classic Roblox experience. Play with friends in an immersive 3D environment, or create your own game. Your imagination is the only limit.</h4>
		</div>
	</div>
</div>
<div class="container text-center my-5">
	<h1 class="mb-5 fw-bold">Social Links</h1>
	<div class="row mb-5">
		<x-socialcard title="YouTube" description="Subscribe to our YouTube channel, where we upload trailers for future events and {{ config('app.name') }} gameplay videos." link="https://www.youtube.com/virtubrick?sub_confirmation=1" />
		<x-socialcard title="Twitter" description="Follow us on Twitter. Here you can recieve important updates about {{ config('app.name') }} and receive announcements for events, potential downtime, status reports, etc." link="https://twitter.com/intent/user?screen_name=virtubrick" />
		<x-socialcard title="Discord" description="Join our Discord server. This is the place where you can engage with the rest of our community, or just hang out with friends." link="https://www.discord.gg/jBRHAyp" />
	</div>
</div>
@endsection
