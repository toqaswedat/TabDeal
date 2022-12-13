<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\TopicController;
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
Route::get('/topic-byUrl', [TopicController::class,'getTopicsByUrlHiw']);
Route::get('/topic-byUrl-video', [TopicController::class,'getTopicsByUrlVideos']);