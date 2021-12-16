@extends('layouts.app')

@section('extra-headers')
<noscript><meta http-equiv="refresh" content="0; url=javascript"/></noscript>
@endsection

@section('content')
<script src="{{ asset('js/app.js') }}" integrity="{{ Sri::hash('js/app.js') }}" crossorigin="anonymous"></script>
@endsection
