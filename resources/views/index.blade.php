@extends('layouts.main')

@section('content')
    <h1 class="text-4xl text-center text-blue-600">
        Welcome @auth {{ auth()->user()->name }} @endauth to Laravel Application
    </h1>
@endsection
