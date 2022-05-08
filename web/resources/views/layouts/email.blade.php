@php
    $slogan = (View::hasSection('description') ? View::getSection('description') . ' ' : '') . 'Graphictoria is an online social platform for those looking to relive the classic Roblox experience. So what are you waiting for? Join 8k+ other users in reliving the good ol\' days! Graphictoria is not affiliated with or sponsored by Roblox Corporation, all Roblox related indica and slogans belong to Roblox Corporation.';
	
	$cssDriver = Storage::createLocalDriver(['root' => $_SERVER['DOCUMENT_ROOT'] . '/css']);
@endphp
<html class="gtoria-light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<title>Graphictoria{{ View::hasSection('title') ? ' | ' . View::getSection('title') : '' }}</title>
		
		<style>
			{{ $cssDriver->get('Graphictoria.css') }}
			
			html, body {
				background-color: #fff!important;
			}
			
			.emailCard {
				background-color: #b5e0ff;
				margin: 10px;
				text-align: center;
				padding: 10px;
			}
		</style>
	</head>
	<body>
		<div id="gtoria-root">
			@env(['local', 'staging'])
				<h1 style="color:#ff0000;">This is a test message.</h1>
			@endenv
			<div class="card shadow-sm emailCard">
				<h3 class="my-auto">{{ $title }}</h3>
			</div>
			<div class="container text-center p-3">
				@yield('content')
			</div>
			<div class="card shadow-sm emailCard mt-auto">
				<p class="fw-light m-0">Copyright © {{ \Carbon\Carbon::now()->format('Y') }} Graphictoria. All rights reserved.</p>
			</div>
			<p class="text-muted text-center fw-light mt-0 mb-2">Graphictoria is not affiliated with, endorsed by, or sponsored by Roblox Corporation.</p>
		</div>
	</body>
</html>