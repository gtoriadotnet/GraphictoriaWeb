@php
	// TODO: XlXi: load from website configuration?
	
	$routes = [
		[
			"label" => "Games",
			"location" => "games"
		],
		[
			"label" => "Shop",
			"location" => "shop"
		],
		[
			"label" => "Forum",
			"location" => "forum"
		]
	];
@endphp

<div class="fixed-top">
	@live
		@moderator
			<style>
				@media (min-width: 768px) {
					.virtubrick-admin-nav > .nav-item:not(:first-child) {
						border-left: 1px solid #666;
					}
				}
				
				@media (max-width: 768px) {
					.virtubrick-admin-nav {
						flex-direction: unset;
						flex-wrap: wrap;
					}
					.virtubrick-admin-nav > .nav-item {
						padding: 0 8px 0 8px;
					}
				}
				
				.virtubrick-admin-nav > .nav-item > a,
				.virtubrick-admin-nav > .nav-item > p,
				.virtubrick-admin-nav > .nav-item > span
				{
					color: #ccc;
				}
				
				.virtubrick-admin-nav > .nav-item > a:hover {
					color: #eee;
				}
				
				.virtubrick-admin-usagebar {
					width: 100px;
					height: 10px;
				}
			</style>
			<div class="navbar navbar-dark bg-dark border-0 py-1">
				<div class="container-md navbar-expand-md text-light">
					<span class="badge rounded-pill bg-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Administration and Management Bar">QAaMB</span>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#virtubrick-admin-nav" aria-controls="virtubrick-admin-nav" aria-expanded="false" aria-label="Toggle navigation" style="font-size: 14px;">
						<span>Toggle</span>
					</button>
					<div class="collapse navbar-collapse" id="virtubrick-admin-nav">
						<ul class="navbar-nav virtubrick-admin-nav ms-auto">
							@yield('quick-admin')
							<li class="nav-item">
								<a href="{{ route('admin.dashboard') }}" class="nav-link py-0"><i class="fa-solid fa-gavel"></i></a>
							</li>
							@admin
								<li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="<strong>Updates every minute</strong><br/>{{ \App\Helpers\QAaMBHelper::getMemoryUsage() }}">
									<span class="px-md-2 d-flex" style="height:24px;">
										<x-admin.usage-bar :stat="\App\Helpers\QAaMBHelper::getMemoryPercentage()" />
										<i class="my-auto fa-solid fa-gear"></i>
									</span>
								</li>
								<li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="<strong>Updates every minute</strong><br/>{{ \App\Helpers\QAaMBHelper::getCpuUsage() }}">
									<span class="px-md-2 d-flex" style="height:24px;">
										<x-admin.usage-bar :stat="\App\Helpers\QAaMBHelper::getCpuPercentage()" />
										<i class="my-auto fa-solid fa-microchip"></i>
									</span>
								</li>
							@endadmin
						</ul>
					</div>
				</div>
			</div>
		@endmoderator
	@endlive
	<div
		class="navbar virtubrick-navbar navbar-expand-md shadow-sm"
	>
		<div class="container-md">
			@live
				<a class="navbar-brand" href="/">
					<img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}" width="43" height="43" draggable="false"/>
				</a>
			@else
				<i class="navbar-brand">
					<img src="{{ asset('/images/logo.png') }}" alt="{{ config('app.name') }}" width="43" height="43" draggable="false"/>
				</i>
			@endlive
			@live
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#virtubrick-nav" aria-controls="virtubrick-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			@endlive
			@live
				<div class="collapse navbar-collapse" id="virtubrick-nav">
			@endlive
				<ul class="navbar-nav me-auto">
					@live
						@foreach($routes as $route)
							@php
								$route = (object)$route;
							@endphp
							<li class="nav-item">
								<a @class(['nav-link', 'active'=>str_starts_with(Request::path(), $route->location)]) href="{{ url('/' . $route->location) }}">{{ $route->label }}</a>
							</li>
						@endforeach
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="virtubrick-nav-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">More</a>
							<ul class="dropdown-menu virtubrick-nav-dropdown" area-labelledby="virtubrick-nav-dropdown">
								@auth
									<li><a @class(['dropdown-item', 'active'=>str_starts_with(Request::path(), 'my/create')]) href="{{ url('/my/create') }}">Create</a></li>
								@endauth
								<li><a @class(['dropdown-item', 'active'=>str_starts_with(Request::path(), 'groups')]) href="{{ url('/groups') }}">Groups</a></li>
								<li><a @class(['dropdown-item', 'active'=>str_starts_with(Request::path(), 'users')]) href="{{ url('/users') }}">Users</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a></li>
								<li><a class="dropdown-item" href="https://blog.virtubrick.net" target="_blank" rel="noreferrer">Blog</a></li>
							</ul>
						</li>
					@else
						<li class="nav-item">
							<a class="nav-link" href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a>
						</li>
					@endlive
				</ul>
				@live
					@auth
						<div id="virtubrick-nav-searchbar" class="virtubrick-search"></div>
						<ul class="navbar-nav ms-auto me-2">
							<li class="nav-item">
								<a @class(['nav-link', 'active'=>str_starts_with(Request::path(), 'my/friends')]) href="{{ url('/my/friends') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Friends">
									@php
										$friendRequestCount = Auth::user()->getFriendRequests()->count();
									@endphp
									@if($friendRequestCount > 0)
										<span class="position-relative top-0 start-100 translate-middle badge rounded-pill bg-danger">
											{{
												$friendRequestCount > 99 ? '99+' : $friendRequestCount
											}}
										</span>
									@endif
									<i class="fa-solid fa-user-group"></i>
								</a>
							</li>
							<li class="nav-item">
								<a @class(['nav-link', 'active'=>str_starts_with(Request::path(), 'my/messages')]) href="{{ url('/my/messages') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Messages">
									<i class="fa-solid fa-inbox"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notifications">
									<i class="fa-solid fa-bell"></i>
								</a>
							</li>
						</ul>
						<div class="d-md-flex">
							<a href="{{ route('user.transactions') }}" class="my-auto me-2 text-decoration-none">
								<span>
									<p class="virtubrick-tokens" href="" data-bs-toggle="tooltip" data-bs-placement="bottom" title="You have {{ number_format(Auth::user()->tokens) }} tokens. Your next reward is in {{ Auth::user()->next_reward->diffForHumans(['syntax' => Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}.">
										{{ \App\Helpers\NumberHelper::Abbreviate(Auth::user()->tokens) }}
									</p>
								</span>
							</a>
							<div class="dropdown">
								<a class="nav-link dropdown-toggle virtubrick-user-dropdown px-0 px-md-3" href="#" id="virtubrick-user-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">
									<x-user-circle :user="Auth::user()" :statusIndicator=false />
								</a>
								<ul class="dropdown-menu virtubrick-user-dropdown" area-labelledby="virtubrick-user-dropdown">
									<li><a class="dropdown-item" href="{{ Auth::user()->getProfileUrl() }}">Profile</a></li>
									<li><a class="dropdown-item" href="{{ route('user.avatarEditor') }}">Character</a></li>
									<li><a class="dropdown-item" href="{{ route('user.settings') }}">Settings</a></li>
									<li><hr class="dropdown-divider"></li>
									<li><a class="dropdown-item" href="{{ route('auth.logout') }}">Sign out</a></li>
								</ul>
							</div>
						</div>
					@else
						<a class="btn btn-success" href="/login">Login / Sign up</a>
					@endauth
				@endlive
			@live
				{{-- virtubrick-nav --}}
				</div>
			@endlive
		</div>
	</div>
</div>
<div
	class="virtubrick-nav-margin"
	@moderator
	style="padding-top:32px"
	@endmoderator
></div>
@foreach(App\Models\Banner::all() as $banner)
	<div @class(['alert', 'alert-' . $banner->style, 'virtubrick-alert', 'alert-dismissible' => $banner->dismissable])>
		<p class="mb-0">{{ \App\Helpers\MarkdownHelper::parse($banner->message) }}</p>
		@if($banner->dismissable)
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		@endif
	</div>
@endforeach
@live
	@if(config('app.testenv'))
		<div class="alert alert-yellow virtubrick-alert">
			<p class="mb-0 text-dark">You have found the VirtuBrick testing site! (<a href="{{ route('testing.info') }}" class="text-decoration-none">More Info</a>).</p>
		</div>
	@endif
@endlive
<noscript>
<div class="container my-2">
<div class="alert alert-danger virtubrick-alert virtubrick-error-popup mx-5">This website requires Javascript to work properly. Please enable Javascript or download a Javascript compatible browser.</div>
</div>
</noscript>