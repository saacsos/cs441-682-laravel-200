@extends('layouts.main')

@section('content')

    <h1 class="text-4xl mx-10 mb-2">Artist List</h1>

    <div class="mx-10 my-2">
        <a href="{{ route('artists.create') }}"
           class="border px-4 py-2 bg-blue-200"
        >
            + Artist
        </a>
    </div>

    <ul class="mx-10">
        @foreach($artists as $artist)
            <li class="border border-gray-400 p-2">
                {{ $loop->iteration }}.
                <a href="{{ route('artists.show', ['artist' => $artist]) }}">
                    {{ $artist->name }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
