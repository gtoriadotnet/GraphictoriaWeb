@props([
	'id',
	'definition',
	'nolabel',
	'value'
])

<div class="col-6">
	@if(!isset($nolabel) || !$nolabel)
		<label for="vb-{{ $id }}-search" class="form-label">{{ $definition }}</label>
	@endif
	<form method="GET" action="{{ route('admin.usersearch') }}">
		<div class="input-group mb-3">
			<input
				type="text"
				class="form-control"
				placeholder="{{ $definition }} Here"
				aria-label="{{ $definition }} Here"
				name="{{ $id }}" id="vb-{{ $id }}-search"
				aria-describedby="vb-{{ $id }}-search-btn"
				@if(isset($value))
					value="{{ $value }}"
				@endif
			>
			<button type="submit" class="btn btn-primary" type="button" id="vb-{{ $id }}-search-btn">Search</button>
		</div>
	</form>
</div>