<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UrlShortenerController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['middleware' => 'api'], function () {

    Route::get('/short/{shortUrl}', [UrlShortenerController::class, 'show'])
        ->where('shortUrl', '^[A-Za-z0-9]{6}$');

    Route::post('/short', [UrlShortenerController::class, 'store']);
});

