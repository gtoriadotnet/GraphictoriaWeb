@props([
	'user',
	'statusIndicator',
	'size'
])

@php
	// 37x37 Default
	$size = isset($size) ? $size : 37;
	$showStatus = (!isset($statusIndicator) || $statusIndicator);
	$classes = [
		'img-fluid',
		'border',
		'me-1',
		'virtubrick-user-circle'
	];
	
	if($showStatus)
	{
		// TODO: XlXi: Advanced user presence. (in games, in studio, etc)
		if($user->isOnline())
			$classes = array_merge($classes, ['border-vb-online-website', 'border-2']);
	}
@endphp

<span class="d-flex align-items-center">
	{{-- TODO: XlXi: User headshots --}}
	<img
		src="{{ asset('images/testing/headshot.png') }}"
		@class($classes)
		width="{{ $size }}"
		height="{{ $size }}"
	>
	<p>{{ $user->username }}</p>
</span>