<?php

use App\Http\Controllers\AddDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\Item_reportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\TopicController;
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

// Route::post('/get_favourite', [UserController:class,'get_favourite'])->middleware('auth:sanctum');

// Route::post('/get_favourite', [UserController::class, 'get_favourite'])->middleware('auth:sanctum');


Route::controller(NotificationController::class)->group(function () {
    Route::get('/notifications', 'notifications');
});
Route::controller(ChatController::class)->group(function () {
    Route::get('/chat_archive', 'chat_archive');
    Route::get('/chat_list', 'chat_list');
});

Route::controller(CityController::class)->group(function () {
    Route::post('/getuserCity', 'get_userCity');
    Route::get('/getCitie', 'getCitie');
});
Route::controller(ItemController::class)->group(function () {
    Route::get('/get_single_post', 'get_single_post');
    Route::get('/get_user_fav_items', 'get_user_fav_items');

});


Route::controller(SectionsController::class)->group(function () {
    Route::get('/getCategories', 'get_categories');
    Route::get('/categories_offers', 'categories_offers');
    Route::get('/offers_num', 'offers_num');
});


Route::controller(UserController::class)->group(function () {
    Route::post('/deals_num', 'deals_number');
    Route::post('/get_favourite', 'get_favourite')->middleware('auth:sanctum');
    Route::get('/get_item_reviews', 'get_item_reviews')->middleware('auth:sanctum');
    Route::get('/get_item_deals', 'get_item_deals');
    Route::post('/add_credits', 'add_credits');
    Route::post('/automatch', 'automatch');
    Route::post('/save_profile', 'save_profile');
    Route::delete('/rem_favourite/{user_id}/{item_id}', 'rem_favourite');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'loginUser')->name('login');
    Route::post('/register','registerUser');
});

Route::controller(AddDataController::class)->group(function () {
    Route::get('/add_data', 'add_data');
});

Route::controller(TopicController::class)->group(function () {
    Route::get('/get_blog', 'get_blog');
    Route::get('/get_terms', 'get_terms');
});

Route::controller(ProfessionController::class)->group(function () {
    Route::get('/get_profession', 'get_profession');
});

Route::controller(BusinessController::class)->group(function () {
    Route::get('/get_business_categories', 'get_business_categories');
});


Route::controller(Item_reportController::class)->group(function () {
    Route::get('/report', 'report');
    Route::get('/get_offers', 'get_offers');
});
