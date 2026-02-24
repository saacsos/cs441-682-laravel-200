<?php

use App\Http\Controllers\API\ArtistController;
use App\Http\Controllers\API\Auth\AuthenticateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// Group ของ Routes ที่ไม่ต้อง login
Route::middleware(['throttle:api'])->as('api.')->group(function () {
    Route::get('/', function () {
        return [
            'version' => '1.0.0',
        ];
    })->name('root');

    Route::post('login', [AuthenticateController::class, 'login'])->name('user.login');
});


// Group ของ Routes ที่จำเป็นต้อง login
Route::middleware(['throttle:api', 'auth:sanctum'])->as('api.')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('me');

    Route::middleware(['ability:ADMIN'])->as('admin.')->group(function () {
        Route::get('/admin/dashboard', function (Request $request) {
            return [
                'message' => "Hello {$request->user()->name}"
            ];
        })->name('dashboard');
    });

    Route::apiResource('artists', ArtistController::class);

    Route::delete('revoke', [AuthenticateController::class, 'revoke'])->name('user.revoke');
});
