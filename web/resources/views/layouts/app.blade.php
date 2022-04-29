@php
    $slogan = (View::hasSection('description') ? View::getSection('description') . ' ' : '') . 'Graphictoria is an online social platform for those looking to relive the classic Roblox experience. So what are you waiting for? Join 8k+ other users in reliving the good ol\' days! Graphictoria is not affiliated with or sponsored by Roblox Corporation, all Roblox related indica and slogans belong to Roblox Corporation.';

	$authenticated = \App\Helpers\AuthHelper::IsAuthenticated(request());
@endphp
<!DOCTYPE html>
<html class="gtoria-{{ View::hasSection('theme') ? View::getSection('theme') : 'light' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<title>Graphictoria{{ View::hasSection('title') ? ' | ' . View::getSection('title') : '' }}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="theme-color" content="#348AFF"/>
		<meta name="author" content="Graphictoria"/>
		<meta name="description" content="{{ $slogan }}"/>
		<meta name="keywords" content="graphictoria, xdiscuss, nostalgia, roblox, gtoria, private server, classic, old roblox, classic roblox, forum, game engine, mmo, classic mmo, old internet"/>
		<meta property="og:title" content="Graphictoria{{ View::hasSection('title') ? ' | ' . View::getSection('title') : '' }}"/>
		<meta property="og:site_name" content="Graphictoria"/>
		<meta property="og:description" content="{{ $slogan }}"/>
		<meta property="og:type" content="website"/>
		<meta property="og:image" content="{{ asset('images/banner.png') }}">
		<meta name="twitter:image" content="{{ asset('images/banner.png') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="twitter:card" content="summary_large_image">
		@once
			<link href="{{ asset('favicon.ico') }}" rel="icon" integrity="{{ Sri::hash('favicon.ico') }}" crossorigin="anonymous" />
			<link href="{{ asset('images/logo.png') }}" rel="apple-touch-icon" integrity="{{ Sri::hash('images/logo.png') }}" crossorigin="anonymous" />
			<link href="{{ asset('manifest.json') }}" rel="manifest" integrity="{{ Sri::hash('manifest.json') }}" crossorigin="anonymous" />
			<link href="{{ mix('css/graphictoria.css') }}" rel="stylesheet" />
		@endonce
		<script src="{{ mix('js/app.js') }}"></script>
		@yield('extra-headers')
		@yield('page-specific')
	</head>
	<body>
		<div id="gtoria-root">
			@if(!isset($noNav))
				@include('layouts.nav')
			@endif
			
			@yield('content')
			
			@if(!isset($noFooter))
				@include('layouts.footer')
			@endif
		</div>
    </body>
</html>