<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Artist::class);
        $artists = Artist::query()->paginate(10);
        return ArtistResource::collection($artists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Artist::class);
        $request->validate([
            'name' => ['required', 'max:255', 'unique:artists,name'],
            'image_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        $artist = new Artist();
        $artist->name = $request->input('name');
        // todo: have to store file first
        $artist->image_path = $request->input('image_path');
        $artist->save();

        $artist = $artist->refresh();
        return new ArtistResource($artist);
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        Gate::authorize('view', $artist);
        return new ArtistResource($artist);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist)
    {
        Gate::authorize('update', $artist);
        $request->validate([
            'name' => [
                'required', 'max:255',
                Rule::unique('artists', 'name')->ignore($artist),
            ],
            'image_path' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        $artist->name = $request->input('name');
        // todo: have to store file first
        $artist->image_path = $request->input('image_path');
        if ($artist->isDirty()) {
            $artist->save();
            $artist = $artist->refresh();
        }
        return new ArtistResource($artist);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        Gate::authorize('delete', $artist);
        $artist->delete();
        return response(null, 204);
    }
}
