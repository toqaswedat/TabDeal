<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getCitie', [CityController::class, 'get_cities']);

Route::post('/getuserCity', [CityController::class, 'get_userCity']);

Route::get('/getCategories', [SectionsController::class, 'get_categories']);

Route::post('/ledger', [UserController::class, 'ledger']);


Route::post('/login', [UserController::class, 'login']);
