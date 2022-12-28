@props([
	'stat'
])

<div class="my-auto rounded-1 bg-secondary border border-light right-0 me-1 position-relative virtubrick-admin-usagebar">
	@php
		$usage_bar_color = 'bg-primary';
		$usage_bar_usage = $stat * 100;
		
		if($usage_bar_usage <= 25)
			$usage_bar_color = 'bg-success'; // Green
		elseif($usage_bar_usage > 25 && $usage_bar_usage <= 75)
			$usage_bar_color = 'bg-warning'; // Orange
		elseif($usage_bar_usage > 75)
			$usage_bar_color = 'bg-danger';  // Red
	@endphp
	<div
		class="{{ $usage_bar_color }} rounded-1 position-absolute virtubrick-admin-usagebar"
		style="width:{{ $usage_bar_usage }}%!important;height:8px!important;"
	></div>
</div>