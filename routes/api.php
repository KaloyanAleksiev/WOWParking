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

Route::get('available_slots', 'Api\SlotController@availableSlots');
Route::get('check_vehicle_fees/{register_number}', 'Api\VehicleController@checkVehicleFees');
Route::post('register_vehicle', 'Api\VehicleController@registerVehicle');
Route::patch('sign_out_vehicle', 'Api\VehicleController@signOutVehicle');

Route::fallback(function(){
    return response()->json(['message' => 'Sorry, the page you are looking for could not be found.'], 404);
})->name('api.fallback.404');
