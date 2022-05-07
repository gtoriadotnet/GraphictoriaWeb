@php
	$classes = ['card', 'graphictoria-small-card', 'shadow-sm'];
	
	if(isset($attributes['class']))
		$classes = array_merge($classes, explode(' ', $attributes['class']));
@endphp
<div @class($classes)>
	<div class="card-body text-center">
		<h5 class="card-title fw-bold">{{ isset($title) ? $title : $attributes['title'] }}</h5>
		<hr class="mx-5"/>
		<p class="card-text">{{ $body }}</p>
		{{ $footer }}
	</div>
</div>