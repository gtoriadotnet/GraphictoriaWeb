@props([
	'label'
])

<div class="row py-2 border-top">
	<div class="col-6">
		<p class="fw-bold">{{ $label }}</p>
	</div>
	<div class="col-6">
		{{ $slot }}
	</div>
</div>