<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TopicController;
use App\Models\Notification;
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

Route::get('/business', [BusinessController::class, 'getAllBusinesses']);
Route::get('/item-byId', [ItemController::class,'getItemById']);
Route::get('/update-notification-seen', [NotificationController::class, 'updateNotificationSeen']);



Route::controller(TopicController::class)->group( function(){
    Route::get('/topic-byUrl', 'getTopicsByUrlHiw');
    Route::get('/topic-byUrl-video', 'getTopicsByUrlVideos'); 
});
Route::controller(MessageController::class)->group( function(){ 
    Route::get('/unseen-message', 'getMessageUnseen');
    Route::get('/update-message-seen', 'updateMessage');
});



