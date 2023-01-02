<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(BusinessController::class)->group(function(){
    Route::get('/business_user', 'getAllBusinesses');
    Route::put('/update_profile_business_', 'updateBusinessProfile');
});
Route::get('/dashboard', [DashboardController::class, 'Dashboard']);
Route::controller(ProfileController::class)->group(function(){
    Route::get('/profile', 'getUsersProfile');
    Route::put('/update_profle', 'updateProfile');
    Route::put('/update_profle_business', 'updateProfileBusiness');
    Route::put('/update_current_balance', 'updateCurrentBalance');
    Route::put('/update_profile_business_email', 'updateProfileBusinessEmail');
});

Route::controller(DealController::class)->group(function(){
    Route::put('/update_trade', 'updateTrade');
    Route::put('/change_status', 'changeStatus');
});


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
    Route::put('/update_post_offer', 'updatePostOffer');
    Route::put('/update_demand_offer', 'updateDemandOffer');
});