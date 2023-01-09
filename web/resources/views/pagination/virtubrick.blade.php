@if ($paginator->hasPages())
	<ul class="list-inline mx-auto mt-3">
		<li class="list-inline-item">
			<a @class(['btn', 'btn-secondary', 'disabled' => $paginator->onFirstPage()]) {!! !$paginator->onFirstPage() ? 'href="' . $paginator->previousPageUrl() . '"' : '' !!}><i class="fa-solid fa-angle-left"></i></a>
		</li>
		<li class="list-inline-item virtubrick-paginator">
			<span>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
		</li>
		<li class="list-inline-item">
			<a @class(['btn', 'btn-secondary', 'disabled' => !$paginator->hasMorePages()]) {!! $paginator->hasMorePages() ? 'href="' . $paginator->nextPageUrl() . '"' : '' !!}><i class="fa-solid fa-angle-right"></i></a>
		</li>
	</ul>
@endif