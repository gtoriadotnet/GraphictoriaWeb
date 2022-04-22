@php
	// TODO: load from website configuration?
    $routes = [
		[
			"label" => "About Us",
			"location" => "/legal/about-us"
		],
		[
			"label" => "Terms of Use",
			"location" => "/legal/terms-of-use"
		],
		[
			"label" => "Privacy Policy",
			"location" => "/legal/privacy-policy"
		],
		[
			"label" => "DMCA",
			"location" => "/legal/dmca"
		],
		[
			"label" => "Support",
			"location" => "/support"
		],
		[
			"label" => "Blog",
			"location" => 'https://blog.gtoria.net'
		],
	]
@endphp

<div class="footer mt-auto pt-3 text-center shadow-lg">
	<div class="container">
		<h4 class="fw-bold mb-0">Graphictoria</h4>
		@live
			<p class="text-muted fw-bold mb-0 mt-1">
				@foreach($routes as $index => $route)
					@php
						// HACK
						$route = (object)$route;
					@endphp
					<a class="text-decoration-none fw-normal" href="{{ url($route->location) }}"{{ $route->label == 'Blog' ? ' target="_blank"' : '' }}>{{ $route->label }}</a>
					@if($index != array_key_last($routes))
						{{ ' | ' }}
					@endif
				@endforeach
			</p>
		@endlive
		<hr class="mx-auto my-2 w-25"/>
		<p class="text-muted fw-light m-0">Copyright © {{ \Carbon\Carbon::now()->format('Y') }} Graphictoria. All rights reserved.</p>
		<p class="text-muted fw-light m-0">Graphictoria is not affiliated with, endorsed by, or sponsored by Roblox Corporation. The usage of this website signifies your acceptance of the <a class="text-decoration-none fw-normal" href="{{ url('/legal/terms-of-use') }}">Terms of Use</a> and our <a class="text-decoration-none fw-normal" href="{{ url('/legal/privacy-policy') }}">Privacy Policy</a>.</p>
		<div class="my-1">
			<a class="mx-1" href="https://www.youtube.com/graphictoria?sub_confirmation=1" rel="noreferrer" target="_blank"><img src="{{ asset('/images/YouTube.svg') }}" alt="YouTube" height="22" width="28"></img></a>
			<a class="mx-1" href="https://twitter.com/intent/user?screen_name=gtoriadotnet" rel="noreferrer" target="_blank"><img src="{{ asset('/images/Twitter.svg') }}" alt="Twitter" height="28" width="28"></img></a>
			<a class="mx-1" href="https://discord.gg/q666a2sF6d" rel="noreferrer" target="_blank"><img src="{{ asset('/images/Discord.svg') }}" alt="Discord" height="28" width="28"></img></a>
		</div>
	</div>
</div>