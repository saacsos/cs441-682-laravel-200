<?php

use App\Http\Controllers\API\ArtistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return [
        'version' => '1.0.0',
    ];
})->middleware(['throttle:api']);

Route::apiResource('artists', ArtistController::class);
