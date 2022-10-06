<div class="container graphictoria-smaller-page fixed-top">
	<div class="navbar navbar-expand-lg graphictoria-navbar rounded m-2 graphictoria-blognav" id="gt-blog-nav">
		<div class="container-fluid px-4">
			<a class="navbar-brand" href="{{ route('blog.home') }}">
				<img src="{{ asset('/images/logo.png') }}" alt="Graphictoria" width="43" height="43" draggable="false"/>
				&nbsp;Blog
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#graphictoria-nav" aria-controls="graphictoria-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-md-center" id="graphictoria-nav">
				<ul class="navbar-nav mx-auto">
					<li class="nav-item">
						<a class="nav-link active" href="{{ url('/') }}">Home</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://gtoria.net">Main Site</a>
					</li>
				</ul>
				<ul class="navbar-nav">
					<li class="nav-item">
						{{-- TODO: XlXi: Replace this with a route. --}}
						<a class="nav-link text-danger" target="_blank" href="{{ url('/rss') }}"><i class="fa-solid fa-square-rss"></i></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div
	class="graphictoria-blog shadow-sm"
	@if(View::hasSection('image-replacement'))
	style="background-image: url('{{ View::getSection('image-replacement') }}')!important;"
	@endif
></div>