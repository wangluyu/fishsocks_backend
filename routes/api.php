<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('auth')->group(function($router) {
    $router->post('getRegisterCode', 'AuthController@getRegisterCode');
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
});

Route::namespace('Api')->prefix('v1')->group(function ($router) {
    Route::middleware('refresh.token')->group(function($router) {
        $router->get('index','IndexController@index');
        $router->post('changePortPassword','IndexController@changePortPassword');
    });
});