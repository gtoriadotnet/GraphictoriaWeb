@extends('layouts.app')

@section('title', $title)

@section('page-specific')
@endsection

@section('content')
<h1>this is {{ $user->username }}'s profile</h1>
@endsection