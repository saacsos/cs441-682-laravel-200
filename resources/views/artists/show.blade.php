@extends('layouts.main')

@section('content')
    <section class="mx-10">
        <h1 class="text-4xl">
            Artist: {{ $artist->name }}
        </h1>
        <p>Image: {{ $artist->image_path }}</p>
    </section>
    <section class="mx-10 mt-8">
        <ul class="mx-10">
            @foreach($artist->songs as $song)
                <li class="border border-b-gray-400 p-2">
                    {{ $song->title }}
                    ({{ $song->duration }} s.)
                </li>
            @endforeach
        </ul>
    </section>
@endsection
