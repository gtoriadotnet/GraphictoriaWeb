@extends('layouts.app', ['title' => 'Secure', 'adminPage' => true])

@section('content')
	<form class="mx-auto my-auto d-flex flex-column">
		<input type="email" class="form-control mb-3" id="email" placeholder="Email" />
		<input type="password" class="form-control mb-3" id="password" placeholder="Password" />
		<button type="submit" class="btn btn-primary">Login</button>
	</form>
@endsection
