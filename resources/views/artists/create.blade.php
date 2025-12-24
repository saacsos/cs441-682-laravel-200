@extends('layouts.main')

@section('content')
    <section class="mx-10">
        <h1 class="text-3xl">
            Add New Artist
        </h1>

        <form action="{{ route('artists.store') }}"
              method="POST"
              class="p-4 bg-yellow-100"
        >
            @csrf

            <div>
                <label for="name">Artist Name</label>
                <input type="text"
                       name="name"
                       id="name"
                       class="border border-gray-400 p-2"
                >
            </div>

            <div class="mt-4">
                <button type="submit"
                        class="border px-4 py-2 bg-blue-200 cursor-pointer"
                >
                    Add Artist
                </button>
            </div>

        </form>
    </section>
@endsection
