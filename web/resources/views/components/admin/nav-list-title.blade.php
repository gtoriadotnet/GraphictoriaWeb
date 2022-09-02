@props([
	'name'
])

<span class="gt-admin-navtitle">{{ $name }}</span>
<ul class="gt-admin-nav nav flex-column">
	{{ $slot }}
</ul>