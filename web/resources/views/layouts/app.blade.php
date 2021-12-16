<!DOCTYPE html>
<html class="{{ isset($jsPage) ? 'gtoria-dark' : 'gtoria-light' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
			@yield('content')
		</div>
    </body>
</html>