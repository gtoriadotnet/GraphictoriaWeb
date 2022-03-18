<!DOCTYPE html>
<html class="gtoria-light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<title>Graphictoria{{ isset($title) ? ' | ' . $title : '' }}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="theme-color" content="#348AFF"/>
		<meta name="author" content="Graphictoria"/>
		<meta name="description" content="Graphictoria is an online social platform for those looking to relive the classic Roblox experience. So what are you waiting for? Join 1.7k+ other users in reliving the good ol' days! Graphictoria is not affiliated with or sponsored by Roblox Corporation, all Roblox related indica and slogans belong to Roblox Corporation."/>
		<meta name="keywords" content="Graphictoria, XDiscuss, nostalgia, roblox, gtoria, private server"/>
		<meta property="og:title" content="Graphictoria{{ isset($title) ? ' | ' . $title : '' }}"/>
		<meta property="og:site_name" content="Graphictoria"/>
		<meta property="og:description" content="Graphictoria is an online social platform for those looking to relive the classic Roblox experience. So what are you waiting for? Join 1.7k+ other users in reliving the good ol' days! Graphictoria is not affiliated with or sponsored by Roblox Corporation, all Roblox related indica and slogans belong to Roblox Corporation."/>
		<meta property="og:type" content="website"/>
		<meta property="og:image" content="{{ asset('images/banner.png') }}">
		<meta name="twitter:image" content="{{ asset('images/banner.png') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="twitter:card" content="summary_large_image">
		@once
			<link href="{{ asset('favicon.ico') }}" rel="icon" integrity="{{ Sri::hash('favicon.ico') }}" crossorigin="anonymous" />
			<link href="{{ asset('images/logo.png') }}" rel="apple-touch-icon" integrity="{{ Sri::hash('images/logo.png') }}" crossorigin="anonymous" />
			<link href="{{ asset('manifest.json') }}" rel="manifest" integrity="{{ Sri::hash('manifest.json') }}" crossorigin="anonymous" />
			<link href="{{ asset('css/graphictoria.css') }}" rel="stylesheet" integrity="{{ Sri::hash('css/graphictoria.css') }}" crossorigin="anonymous" />
		@endonce
		@yield('extra-headers')
	</head>
	<body>
		<div id="gtoria-root">
			<nav class="navbar graphictoria-navbar fixed-top shadow-sm">
				<div class="container-fluid">
					<a class="navbar-brand" href="/">Customer Service & Moderation</a>
				</div>
			</nav>
			<div class="graphictoria-nav-margin"></div>
			<div class="row">
				<div class="col-3">
					<div class="card mb-4">
						<div class="card-header bg-primary text-white">
							Customer Service
						</div>
						<div class="card-body">
							<div class="mb-2 d-flex flex-column">
								<p class="fw-bold">User Admin</p>
								<hr class="my-1" />
								<a class="text-decoration-none fw-normal" href="/Users/LookupTool">Find user</a>
								<a class="text-decoration-none fw-normal" href="/Users/AssetTransfer">User Asset Transfer</a>
								<a class="text-decoration-none fw-normal" href="/AssetTransfer">Asset Transfer</a>
								<a class="text-decoration-none fw-normal" href="/Users/GroupTransactions">User/Group Transactions</a>
								<a class="text-decoration-none fw-normal" href="/Users/Trades">User Trades</a>
								<a class="text-decoration-none fw-normal" href="/Users/SendMessage">Send Personal Message</a>
								<a class="text-decoration-none fw-normal" href="/Users/Messages">User Messages</a>
								<a class="text-decoration-none fw-normal" href="/PresetMessages">Preset Message</a>
							</div>
							<div class="mb-2 d-flex flex-column">
								<p class="fw-bold">Groups</p>
								<hr class="my-1" />
								<a class="text-decoration-none fw-normal" href="/Groups/Admin">Group Admin</a>
							</div>
							<div class="mb-2 d-flex flex-column">
								<p class="fw-bold">Asset</p>
								<hr class="my-1" />
								<a class="text-decoration-none fw-normal" href="/Users/Scrub">Asset Scrub</a>
								<a class="text-decoration-none fw-normal" href="/Users/Inventory">Inventory</a>
								<a class="text-decoration-none fw-normal" href="/Users/CollectiblesAudit">Collectibles Audit</a>
							</div>
							<div class="mb-2 d-flex flex-column">
								<p class="fw-bold">Misc</p>
								<hr class="my-1" />
								<a class="text-decoration-none fw-normal" href="/Users/Adjust">Adjust Assets, Credit & Currency</a>
							</div>
						</div>
					</div>
					<div class="card mb-4">
						<div class="card-header bg-info text-white">
							Moderator
						</div>
						<div class="card-body">
							<div class="mb-2 d-flex flex-column">
								<p class="fw-bold">Review</p>
								<hr class="my-1" />
								<a class="text-decoration-none fw-normal" href="/AssetQueue">Asset Queue</a>
								<a class="text-decoration-none fw-normal" href="/ReportQueue">Report Queue</a>
								<a class="text-decoration-none fw-normal" href="/Perfmon">Performance</a>
							</div>
							<div class="mb-2 d-flex flex-column">
								<p class="fw-bold">Moderation</p>
								<hr class="my-1" />
								<a class="text-decoration-none fw-normal" href="/Moderation/AuditLog">Audit Log</a>
								<a class="text-decoration-none fw-normal" href="/Moderation/EditFilter">Content Filter</a>
								<a class="text-decoration-none fw-normal" href="/Moderation/MachineConfig">Machine Config</a>
								<a class="text-decoration-none fw-normal" href="/Moderation/Alert">Site-wide alert</a>
							</div>
							<div class="mb-2 d-flex flex-column">
								<p class="fw-bold">Configuration</p>
								<hr class="my-1" />
								<a class="text-decoration-none fw-normal" href="/Moderation/EditSettings">Client App Settings</a>
								<a class="text-decoration-none fw-normal" href="/Moderation/EditKeys">Security Keys</a>
								<a class="text-decoration-none fw-normal" href="/Moderation/EditIps">Whitelisted Arbiter IPs</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-9">
				</div>
			</div>
			<div class="graphictoria-nav-margin"></div>
			@yield('content')
		</div>
    </body>
</html>