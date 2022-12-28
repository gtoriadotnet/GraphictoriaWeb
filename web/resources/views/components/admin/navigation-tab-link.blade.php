@props([
	'label',
	'route'
])

<li class="nav-item">
	<a
		@class([
			'nav-link',
			'active' => str_starts_with(Request::path(), substr(parse_url($route, PHP_URL_PATH), 1))
		])
		aria-current="page"
		href="{{ $route }}"
	>
		{{ $label }}
	</a>
</li>