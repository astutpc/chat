<?php

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





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', 'ChatsController@index')->name('chat');     
  // Route::post('/chat/userlist','ChatsController@userList')->name('chat.userlist');
  // Route::get('/broadcasting/auth', 'ChatsController@auth'); 
  Route::group(['middleware' => 'auth'],function () {
        Route::get('/chat/messages', 'ChatsController@fetchMessages')->name('chat.fetchMessages');
        Route::get('/chat/last/messages', 'ChatsController@appendLastMessage')->name('chat.appendLastMessage');
        Route::post('/chat/send/messages', 'ChatsController@sendMessage')->name('chat.sendMessage');
        Route::post('/chat/send/action', 'ChatsController@actionMessage')->name('chat.message.action');
        Route::post('/chat/user/list', 'ChatsController@userChatList')->name('user.chat.list');
  });