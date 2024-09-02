<?php

use App\Http\Controllers\ContentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('contents', ContentController::class)
    ->only(['index', 'show']);
