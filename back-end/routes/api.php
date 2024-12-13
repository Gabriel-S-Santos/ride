<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [LoginController::class, 'submit']);#->middleware('auth:sanctum');;
Route::post('/login/verify', [LoginController::class, 'verify']);#->middleware('auth:sanctum');;


