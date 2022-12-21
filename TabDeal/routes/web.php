<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/business_user', [BusinessController::class, 'getAllBusinesses']);
Route::get('/dashboard', [DashboardController::class, 'Dashboard']);
Route::get('/profile', [ProfileController::class, 'getUsersProfile']);
Route::put('/update_trade', [DealController::class, 'updateTrade']);


Route::controller(TopicController::class)->group(function () {
    Route::get('/faq_hiw', 'getTopicsByUrlHiw');
    Route::get('/hiw_videos', 'getTopicsByUrlVideos');
    Route::get('/faq_data', 'getTopicsByFaq');
});
Route::controller(MessageController::class)->group(function () {
    Route::get('/get_unseen', 'getMessageUnseen');
    Route::put('/msg_seen', 'updateMessage');
});
Route::controller(NotificationController::class)->group(function () {
    Route::put('/noti_seen', 'updateNotificationSeen');
    Route::get('/noti_check', 'getUnseenNotification');
});
Route::controller(ItemController::class)->group(function () {
    Route::get('/get_all_items', 'getItemById');
    Route::delete('/delete_data', 'deleteData');
});

/*
* The route below is only for testing registration proccess and will be removed once the solution is accepted
*/ 
Route::get('/testUsers',[AuthController::class,'testUsers']);

/*
* The route above is only for testing registration proccess and will be removed once the solution is accepted
*/ 