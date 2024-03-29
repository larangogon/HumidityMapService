<?php

use App\Http\Controllers\Api\CityApiController;
use Illuminate\Support\Facades\Route;

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

Route::post('/cities/humidity', [CityApiController::class, 'getHumidity'])->name('cities.getHumidity');
Route::get('/cities', [CityApiController::class, 'index'])->name('cities');
