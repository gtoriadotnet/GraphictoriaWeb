@php
    $slogan = (View::hasSection('description') ? View::getSection('description') . ' ' : '') . config('app.name') . ' is an online social platform for those looking to relive the classic Roblox experience. So what are you waiting for? Join 8k+ other users in reliving the good ol\' days! ' . config('app.name') . ' is not affiliated with or sponsored by Roblox Corporation, all Roblox related indica and slogans belong to Roblox Corporation.';
@endphp
<!DOCTYPE html>
<html class="vbrick-dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<title>{{ config('app.name') }} Blog{{ View::hasSection('title') ? ' | ' . View::getSection('title') : '' }}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="theme-color" content="#348AFF"/>
		<meta name="author" content="{{ config('app.name') }}"/>
		<meta name="description" content="{{ $slogan }}"/>
		<meta name="keywords" content="virtubrick, xdiscuss, nostalgia, roblox, private server, classic, old roblox, classic roblox, forum, game engine, mmo, classic mmo, old internet"/>
		<meta property="og:title" content="{{ config('app.name') }} Blog{{ View::hasSection('title') ? ' | ' . View::getSection('title') : '' }}"/>
		<meta property="og:site_name" content="{{ config('app.name') }} Blog"/>
		<meta property="og:description" content="{{ $slogan }}"/>
		<meta property="og:type" content="website"/>
		<meta property="og:image" content="{{ asset('images/banner.png') }}">
		<meta name="twitter:image" content="{{ asset('images/banner.png') }}">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="twitter:card" content="summary_large_image">
		@once
			<link href="{{ asset('favicon.ico') }}" rel="icon" integrity="{{ Sri::hash('favicon.ico') }}" crossorigin="anonymous" />
			<link href="{{ asset('images/logo.png') }}" rel="apple-touch-icon" integrity="{{ Sri::hash('images/logo.png') }}" crossorigin="anonymous" />
			<link href="{{ mix('css/VirtuBrick.css') }}" rel="stylesheet" integrity="{{ Sri::hash('css/virtubrick.css') }}" crossorigin="anonymous" />
			<script src="{{ mix('js/Blog.js') }}" integrity="{{ Sri::hash('js/Blog.js') }}" crossorigin="anonymous"></script>
		@endonce
		@yield('extra-headers')
		@yield('page-specific')
	</head>
	<body>
		<div id="vbrick-root">
			@include('layouts.blog.nav')
			
			@yield('content')
			
			@include('layouts.footer')
		</div>
    </body>
</html>