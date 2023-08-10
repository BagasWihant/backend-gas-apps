@extends('v021::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('v021.name') !!}
    </p>
@endsection
