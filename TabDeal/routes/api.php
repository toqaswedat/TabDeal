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
Route::get('/categories_offers', [SectionsController::class, 'categories_offers']);
Route::get('/offers_num', [SectionsController::class, 'offers_num']);

Route::post('/deals_num', [UserController::class, 'deals_number']);
Route::post('/ledger', [UserController::class, 'ledger']);
Route::post('/get_favourite', [UserController::class, 'get_favourite']);
Route::get('/get_item_reviews', [UserController::class, 'get_item_reviews']);
Route::delete('/rem_favourite/{user_id}/{item_id}', [UserController::class, 'rem_favourite']);


Route::post('/login', [UserController::class, 'login']);
