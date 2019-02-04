<?php

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

Route::get('login', 'LoginController@getLogin');
Route::post('login', 'LoginController@postLogin');

Route::group(['middleware' => 'user_auth'], function() {
	Route::get('/', 'WelcomeController@index');	

	Route::any('api/data', 'WelcomeController@getApiData');
	Route::any('api/get-active-calls', 'WelcomeController@getActiveCalls');		
	Route::get('logout', 'LoginController@getLogout');
});