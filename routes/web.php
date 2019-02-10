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

//Forgot Password
Route::get('forgot-password', 'LoginController@forgotPassword')->name('forgotPassword');
Route::post('forgot-password/data', 'LoginController@forgotPasswordData')->name('forgotPasswordData');
Route::get('reset-password/{id}/{key}', 'LoginController@resetPassword')->name('resetPassword');
Route::post('reset-password/data', 'LoginController@resetPasswordData')->name('resetPasswordData');

Route::group(['middleware' => 'user_auth'], function() {
	Route::get('/', 'WelcomeController@index');	
	Route::any('api/data', 'WelcomeController@getApiData');
	Route::any('api/get-active-calls', 'WelcomeController@getActiveCalls');		
	Route::get('logout', 'LoginController@getLogout');

	//Profile
	Route::get('/profile', 'WelcomeController@profile')->name('profile');
	Route::post('/update-profile', 'WelcomeController@updateProfile')->name('updateProfile');
	Route::post('/change-password', 'WelcomeController@changePassword')->name('changePassword');

});