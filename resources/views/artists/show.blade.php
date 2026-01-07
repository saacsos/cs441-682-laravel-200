@extends('layouts.main')

@section('content')
    <section class="mx-10">
        <h1 class="text-4xl">
            Artist: {{ $artist->name }}
        </h1>
        <p>Image: {{ $artist->image_path }}</p>
    </section>
    <section class="flex mx-10">
        @can('update', $artist)
            <a href="{{ route('artists.edit', ['artist' => $artist]) }}"
                class="px-4 py-2 mr-8 inline-block border rounded-md bg-violet-500 text-white"
            >
                Edit Artist
            </a>
        @endcan

        @can('delete', $artist)
            <form onsubmit="return confirm('Are you sure to delete?')"
                action="{{ route('artists.destroy', ['artist' => $artist]) }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="px-4 py-2 inline-block border rounded-md bg-red-500 text-white"
                >
                    Delete Artist
                </button>
            </form>
        @endcan
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
