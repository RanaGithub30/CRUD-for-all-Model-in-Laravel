<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{GenericController};

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

Route::controller(GenericController::class)->prefix('{model}')->group(function () {
    Route::get('/', 'index'); 
    Route::get('/{id}', 'show'); 
    Route::post('/', 'store'); 
    Route::post('/{id}', 'update'); 
    Route::delete('/{id}', 'destroy'); 
    Route::post('/file/upload', 'fileUpload');
});