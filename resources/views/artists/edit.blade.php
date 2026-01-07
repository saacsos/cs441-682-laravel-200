@extends('layouts.main')

@section('content')
    <section class="mx-10">
        <h1 class="text-3xl">
            Edit Artist
        </h1>

        <form action="{{ route('artists.update', ['artist' => $artist]) }}"
              method="POST"
              class="p-4 bg-yellow-100"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="name">Artist Name</label>
                @error('name')
                <p class="text-red-500 text-sm">
                    {{ $message }}
                </p>
                @enderror
                <input type="text"
                       name="name"
                       id="name"
                       class="border border-gray-400 p-2 @error('name') border-4 border-red-400 @enderror"
                       value="{{ old('name', $artist->name) }}"
                >
            </div>

            <div class="mt-4">
                <button type="submit"
                        class="border px-4 py-2 bg-blue-200 cursor-pointer"
                >
                    Update
                </button>
            </div>

        </form>
    </section>
@endsection
