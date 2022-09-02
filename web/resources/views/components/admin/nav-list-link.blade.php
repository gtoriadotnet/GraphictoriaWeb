@props([
	'name',
	'route',
	'parameters' => ''
])

<li><a class="nav-link" href="{{ route($route, explode(';', $parameters)) }}">{{ html_entity_decode($name) }}</a></li>