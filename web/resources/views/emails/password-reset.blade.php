@extends('layouts.email')

@section('title', 'Graphictoria Password Reset')

@section('content')
<h4>Hello, <b>{{ $user }}</b>!</h4>
<p>We've received your password reset request. Simply just click the button below to change your password.</p>
<a class="btn btn-primary mt-2" href="{{ $link }}">Reset Password</a>
<p class="mt-3" style="color:#ff0000;">
	If you did not submit this request, you can ignore this email.
</p>
@endsection