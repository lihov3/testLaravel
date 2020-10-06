<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'Api\Auth\LoginController@login');
Route::post('registration', 'Api\Auth\LoginController@registration');

Route::group(['middleware' => ['jwt.verify']], function(){

	Route::post('send_invitation', 'Api\AccountController@sendInvitation');
	Route::get('show_invitation', 'Api\AccountController@showInvitation');
	Route::post('accept_invitation', 'Api\AccountController@acceptInvitation');
	Route::post('cancel_invitation', 'Api\AccountController@cancelInvitation');
	//Route::post();

});