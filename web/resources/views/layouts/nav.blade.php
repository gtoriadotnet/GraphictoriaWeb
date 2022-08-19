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
					.graphictoria-admin-nav > .nav-item:not(:first-child) {
						border-left: 1px solid #666;
					}
				}
				
				@media (max-width: 768px) {
					.graphictoria-admin-nav {
						flex-direction: unset;
						flex-wrap: wrap;
					}
					.graphictoria-admin-nav > .nav-item {
						padding: 0 8px 0 8px;
					}
				}
				
				.graphictoria-admin-nav > .nav-item > a,
				.graphictoria-admin-nav > .nav-item > p,
				.graphictoria-admin-nav > .nav-item > span
				{
					color: #ccc;
				}
				
				.graphictoria-admin-nav > .nav-item > a:hover {
					color: #eee;
				}
				
				.graphictoria-admin-memorybar {
					width: 100px;
					height: 10px;
				}
			</style>
			<div class="navbar navbar-dark bg-dark border-0 py-1">
				<div class="container-md navbar-expand-md text-light">
					<span class="badge rounded-pill bg-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Graphictoria Quick Administration and Management Bar">QAaMB</span>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#graphictoria-admin-nav" aria-controls="graphictoria-admin-nav" aria-expanded="false" aria-label="Toggle navigation" style="font-size: 14px;">
						<span>Toggle</span>
					</button>
					<div class="collapse navbar-collapse" id="graphictoria-admin-nav">
						<ul class="navbar-nav graphictoria-admin-nav ms-auto">
							@yield('quick-admin')
							@admin
								<li class="nav-item">
									<a href="{{ route('admin.diag') }}" class="nav-link py-0">Arbiter Diag</a>
								</li>
							@endadmin
							<li class="nav-item">
								{{-- TODO: XlXi: Make this use route() --}}
								<a href="{{ url('/admin') }}" class="nav-link py-0"><i class="fa-solid fa-gavel"></i></a>
							</li>
							@admin
								<li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="<strong>Only accurate at page load</strong><br/>{{ \App\Helpers\QAaMBHelper::getMemoryUsage() }}">
									<span class="px-md-2 d-flex" style="height:24px;">
										<div class="my-auto rounded-1 bg-secondary border border-light right-0 me-1 position-relative graphictoria-admin-memorybar">
											@php
												$admin_memorybar_color = 'bg-primary';
												$admin_memorybar_usage = \App\Helpers\QAaMBHelper::getMemoryPercentage() * 100;
												
												if($admin_memorybar_usage <= 25)
													$admin_memorybar_color = 'bg-success'; // Green
												elseif($admin_memorybar_usage > 25 && $admin_memorybar_usage <= 75)
													$admin_memorybar_color = 'bg-warning'; // Orange
												elseif($admin_memorybar_usage > 75)
													$admin_memorybar_color = 'bg-danger';  // Red
											@endphp
											<div
												class="{{ $admin_memorybar_color }} rounded-1 position-absolute graphictoria-admin-memorybar"
												style="width:{{ $admin_memorybar_usage }}%!important;height:8px!important;"
											></div>
										</div>
										<i class="my-auto fa-solid fa-gear"></i>
									</span>
								</li>
								<li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="<strong>Only accurate at page load</strong><br/>{{ \App\Helpers\QAaMBHelper::getCpuUsage() }}">
									<span class="px-md-2 d-flex" style="height:24px;">
										<div class="my-auto rounded-1 bg-secondary border border-light right-0 me-1 position-relative graphictoria-admin-memorybar">
											@php
												$admin_cpubar_color = 'bg-primary';
												$admin_cpubar_usage = \App\Helpers\QAaMBHelper::getSystemCpuInfo() * 100;
												
												if($admin_cpubar_usage <= 25)
													$admin_cpubar_color = 'bg-success'; // Green
												elseif($admin_cpubar_usage > 25 && $admin_cpubar_usage <= 75)
													$admin_cpubar_color = 'bg-warning'; // Orange
												elseif($admin_cpubar_usage > 75)
													$admin_cpubar_color = 'bg-danger';  // Red
											@endphp
											<div
												class="{{ $admin_cpubar_color }} rounded-1 position-absolute graphictoria-admin-memorybar"
												style="width:{{ $admin_cpubar_usage }}%!important;height:8px!important;"
											></div>
										</div>
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
		class="navbar graphictoria-navbar navbar-expand-md shadow-sm"
	>
		<div class="container-md">
			@live
				<a class="navbar-brand" href="/">
					<img src="{{ asset('/images/logo.png') }}" alt="Graphictoria" width="43" height="43" draggable="false"/>
				</a>
			@else
				<i class="navbar-brand">
					<img src="{{ asset('/images/logo.png') }}" alt="Graphictoria" width="43" height="43" draggable="false"/>
				</i>
			@endlive
			@live
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#graphictoria-nav" aria-controls="graphictoria-nav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			@endlive
			@live
				<div class="collapse navbar-collapse" id="graphictoria-nav">
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
							<a class="nav-link dropdown-toggle" href="#" id="graphictoria-nav-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">More</a>
							<ul class="dropdown-menu graphictoria-nav-dropdown" area-labelledby="graphictoria-nav-dropdown">
								@auth
									<li><a @class(['dropdown-item', 'active'=>str_starts_with(Request::path(), 'my/create')]) href="{{ url('/my/create') }}">Create</a></li>
								@endauth
								<li><a @class(['dropdown-item', 'active'=>str_starts_with(Request::path(), 'groups')]) href="{{ url('/groups') }}">Groups</a></li>
								<li><a @class(['dropdown-item', 'active'=>str_starts_with(Request::path(), 'users')]) href="{{ url('/users') }}">Users</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="https://discord.gg/q666a2sF6d" target="_blank" rel="noreferrer">Discord</a></li>
								<li><a class="dropdown-item" href="https://blog.gtoria.net" target="_blank" rel="noreferrer">Blog</a></li>
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
						<div id="graphictoria-nav-searchbar" class="graphictoria-search"></div>
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
							<p class="my-auto me-2 text-muted" style="color:#e59800!important;font-weight:bold">
								<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="You have {{ number_format(Auth::user()->tokens) }} tokens. Your next reward is in {{ Auth::user()->next_reward->diffForHumans(['syntax' => Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}.">
									<img src="{{ asset('images/symbols/token.svg') }}" height="20" width="20" class="img-fluid me-1" style="margin-top:-1px" />{{ \App\Helpers\NumberHelper::Abbreviate(Auth::user()->tokens) }}
								</span>
							</p>
							<div class="dropdown">
								<a class="nav-link dropdown-toggle graphictoria-user-dropdown px-0 px-md-3" href="#" id="graphictoria-user-dropdown" role="button" data-bs-toggle="dropdown" area-expanded="false">
									<span class="d-flex align-items-center">
										<img src="{{ asset('images/testing/headshot.png') }}" class="img-fluid border me-1 graphictora-user-circle" width="37" height="37">
										<p>{{ Auth::user()->username }}</p>
									</span>
								</a>
								<ul class="dropdown-menu graphictoria-user-dropdown" area-labelledby="graphictoria-user-dropdown">
									<li><a class="dropdown-item" href="{{ Auth::user()->getProfileUrl() }}">Profile</a></li>
									<li><a class="dropdown-item" href="{{ url('/todo123') }}">Character</a></li>
									<li><a class="dropdown-item" href="{{ url('/my/settings') }}">Settings</a></li>
									<li><hr class="dropdown-divider"></li>
									<li><a class="dropdown-item" href="{{ url('/logout') }}">Sign out</a></li>
								</ul>
							</div>
						</div>
					@else
						<a class="btn btn-success" href="/login">Login / Sign up</a>
					@endauth
				@endlive
			@live
				{{-- graphictoria-nav --}}
				</div>
			@endlive
		</div>
	</div>
</div>
<div
	class="graphictoria-nav-margin"
	@moderator
	style="padding-top:32px"
	@endmoderator
></div>
@foreach(App\Models\Banner::all() as $banner)
	<div @class(['alert', 'alert-' . $banner->style, 'graphictoria-alert', 'alert-dismissible' => $banner->dismissable])>
		<p class="mb-0">{{ \App\Helpers\MarkdownHelper::parse($banner->message) }}</p>
		@if($banner->dismissable)
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		@endif
	</div>
@endforeach
<noscript>
<div class="container my-2">
<div class="alert alert-danger graphictoria-alert graphictoria-error-popup mx-5">A large majority of this website requires Javascript to work properly. Please enable Javascript or download a Javascript compatible browser.</div>
</div>
</noscript>