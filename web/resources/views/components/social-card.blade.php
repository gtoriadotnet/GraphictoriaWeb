<div class="col-lg-4 mb-4 d-flex flex-column align-items-center">
	<svg class="rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="{{ $title }}"><image width="100%" height="100%" href="{{ asset('images/social/' . $title . '.png') }}"></image></svg>
	<h2 class="mt-3">{{ $title }}</h2>
	<p>{{ $description }}</p>
	<a class="btn btn-primary mt-auto" href="{{ $link }}" rel="noreferrer" target="_blank" role="button">View »</a>
</div>