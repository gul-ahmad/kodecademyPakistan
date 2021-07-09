<?php
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SeriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

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

//Route::apiResource('user', App\Http\Controllers\Api\UserController::class);
 Route::group(['middleware' => 'auth:api'], function()
{
    Route::apiResource('user', App\Http\Controllers\Api\UserController::class);
 
}); 
Route::group(['middleware' => 'auth:api'], function()
{
   // Route::apiResource('series', App\Http\Controllers\Api\SeriesController::class);

   //crud routes for series are declred here below
   //becuse i tried to make the crud all routes from simple controller and web.php rotues
   //but only post route was working so helplessly i declared the remaining routes here and declared a controller for it
   //in the api directory
    Route::get('/series', 'App\Http\Controllers\Api\SeriesController@index');
    Route::put('/series/{id}', 'App\Http\Controllers\Api\SeriesController@update');
    Route::delete('/series/{id}', 'App\Http\Controllers\Api\SeriesController@destroy');
 
}); 
//Route::get('profile', App\Http\Controllers\Api\UserController::class);
Route::get('profile', 'App\Http\Controllers\Api\UserController@profile');
Route::put('profile', 'App\Http\Controllers\Api\UserController@updateprofile');

//Route::get('series', 'App\Http\Controllers\Api\UserController@series');