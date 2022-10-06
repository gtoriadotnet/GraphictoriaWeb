@extends('layouts.blog')

@section('content')
<div class="container my-3">
	<div class="input-group px-lg-5">
		<input type="text" class="form-control d-lg-flex" placeholder="Title, content, or tags">
		<button class="btn btn-primary">Search</button>
	</div>
	
	<div class="row row-cols-lg-3 row-cols-sm-2 row-cols-1 my-2">
		@for($i = 0; $i < 30; $i++)
		<a class="col text-decoration-none text-reset py-2" href="#">
			<span>
				<h4 class="mb-0">Blog Post Title</h4>
				<p class="text-muted my-1">This is an example description for this blog post. This text is meant to give the reader a brief view of what the blog post is about. Server-sided truncation will be used to prevent this card form getting too lar...</p>
				<p class="text-primary"><b>Author</b> | October 5, 2022</p>
			</span>
		</a>
		@endfor
	</div>
</div>
@endsection