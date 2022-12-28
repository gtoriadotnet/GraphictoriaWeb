@props([
	'id',
	'definition'
])

<div class="col-6">
	<label for="vb-{{ $id }}-search" class="form-label">{{ $definition }}</label>
	<form method="POST" action="{{ route('admin.usersearch') }}" enctype="multipart/form-data">
		@csrf
		<div class="input-group mb-3">
			<input type="text" class="form-control" placeholder="{{ $definition }} Here" aria-label="{{ $definition }} Here" name="{{ $id }}-search" id="vb-{{ $id }}-search" aria-describedby="vb-{{ $id }}-search-btn">
			<button type="submit" class="btn btn-primary" type="button" name="{{ $id }}-button" id="vb-{{ $id }}-search-btn">Search</button>
		</div>
	</form>
</div>