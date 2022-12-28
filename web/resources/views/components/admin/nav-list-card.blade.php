@props([
	'name',
	'color',
	'roleset'
])

@if(Auth::check() && Auth::user()->hasRoleset($roleset))
<div class="card vb-admin-card">
	<div class="card-header bg-{{ $color }} text-light">
		{{ $name }}
	</div>
	
	{{ $slot }}
</div>
@endif