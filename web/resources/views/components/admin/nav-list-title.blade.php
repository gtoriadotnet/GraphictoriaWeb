@props([
	'name'
])

<span class="vb-admin-navtitle">{{ $name }}</span>
<ul class="vb-admin-nav nav flex-column">
	{{ $slot }}
</ul>