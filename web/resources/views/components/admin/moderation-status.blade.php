@props([
	'user'
])

@php
	$color = 'text-';
	$label = 'Unknown';
	if($user->hasActivePunishment())
	{
		$color .= 'danger';
		$label = $user->getPunishment()->punishment_type->label;
	}
	else
	{
		$color .= 'success';
		$label = 'OK';
	}
@endphp
<p class="{{ $color }}">{{ $label }}</p>