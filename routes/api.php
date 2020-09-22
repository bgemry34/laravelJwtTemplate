<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::POST('login',    [UserController::class, 'login']);
    Route::POST('logout',   [UserController::class, 'logout']);
    Route::POST('refresh',  [UserController::class, 'refresh']);
    Route::POST('me',       [UserController::class, 'me']);
    Route::POST('register', [UserController::class, 'register']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'admins'
], function ($router) {
    Route::POST('login',    [AdminController::class, 'login']);
    Route::POST('logout',   [AdminController::class, 'logout']);
    Route::POST('refresh',  [AdminController::class, 'refresh']);
    Route::POST('me',       [AdminController::class, 'me']);
    Route::POST('register', [AdminController::class, 'register']);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'admin','middleware' => ['assign.guard:admins','jwt.auth']],function ()
{
	Route::get('/demo','AdminController@demo');	
});

Route::group(['prefix' => 'user','middleware' =>     ['assign.guard:admins','jwt.auth']],function ()
{
	Route::get('/demo','UserController@demo');	
});
