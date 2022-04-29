<div class="card graphictoria-small-card shadow-sm">
	<div class="card-body text-center">
		<h5 class="card-title fw-bold">{{ isset($title) ? $title : $attributes['title'] }}</h5>
		<hr class="mx-5"/>
		<p class="card-text">{{ $body }}</p>
		{{ $footer }}
	</div>
</div>