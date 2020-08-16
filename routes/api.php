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
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::middleware('auth:api')->group(function() { 
    Route::get('user/{userId}/detail', 'UserController@show');
});
Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('emailverifyapi');
Route::get('email/resend', 'VerificationApiController@resend')->name('emailresendapi');
