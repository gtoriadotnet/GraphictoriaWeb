@props([
	'user'
])

<div class="card">
	<table class="table virtubrick-table">
		<thead>
			<tr>
				<th scope="col"></th>
				<th scope="col">ID</th>
				<th scope="col">Action</th>
				<th scope="col">Moderator</th>
				<th scope="col">Created</th>
				<th scope="col">Expiration</th>
				<th scope="col">Acknowledged</th>
			</tr>
		</thead>
		<tbody>
			@foreach($user->punishments as $punishment)
				<tr>
					<th scope="col">
						<button class="btn btn-sm p-0 px-1 text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#punishment-collapse-{{ $punishment->id }}" aria-expanded="false" aria-controls="punishment-collapse-{{ $punishment->id }}">
							<i class="fa-solid fa-bars"></i>
						</button>
					</th>
					<th scope="col">&nbsp;{{ $punishment->id }}</th>
					<th scope="col">{{ $punishment->punishment_type->label }}</th>
					<th scope="col">
						<a href="{{ route('admin.useradmin', ['ID' => $punishment->moderator->id]) }}" class="text-decoration-none">
							<x-user-circle :user="$punishment->moderator" :size=24 />
						</a>
					</th>
					<th scope="col">{{ $punishment->reviewed() }}</th>
					<th scope="col">{{ $punishment->expirationStr() }}</th>
					<th scope="col">{{ $punishment->active ? 'No' : 'Yes' }}</th>
				</tr>
				<tr class="collapse" id="punishment-collapse-{{ $punishment->id }}">
					<td colspan="7" class="bg-secondary">
						<div class="mx-2">
							<p><b>Note to User:</b> {{ $punishment->user_note }}</p>
							<p><b>Internal Note:</b> {{ $punishment->internal_note }}</p>
							@if($punishment->pardoned())
								<p><b>Pardoned By:</b> <a class="text-decoration-none" href="{{ route('admin.useradmin', ['ID' => $punishment->pardoner->id]) }}">{{ $punishment->pardoner->username }}</a></p>
								<p><b>Pardoner Note:</b> {{ $punishment->pardoner_note }}</p>
							@endif
							@if($punishment->context->count() > 0)
								<p><b>Abuses:</b></p>
							@endif
							@foreach($punishment->context as $context)
								<div class="card bg-secondary p-2 mb-2 border-1">
									<p><b>Reason:</b> {{ $context->user_note }} (<a href="#" class="text-decoration-none">TODO: audit</a>)</p>
									@if($context->description)
										<p><b>Offensive Item:</b> {{ $context->description }}</p>
									@endif
									@if($context->content_hash)
										<img src="{{ route('content', $context->content_hash) }}" class="img-fluid" width="210" height="210"/>
									@endif
								</div>
							@endforeach
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>