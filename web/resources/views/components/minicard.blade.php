@php
	$classes = ['card'];
	
	if(isset($attributes['class']))
		$classes = array_merge($classes, explode(' ', $attributes['class']));
@endphp
<div @class($classes)>
	<div class="card-body p-2 text-center">
		<h6 class="card-title fw-bold text-uppercase">{{ isset($title) ? $title : $attributes['title'] }}</h6>
		<hr class="mx-5 my-0 mb-2" />
		{{ $body }}
	</div>
</div>