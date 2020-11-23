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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**API MANAGEMENT CONTOLLERS */
Route::middleware('auth:api')->namespace('schema_controllers')->prefix('manage')->group(function(){
    Route::resource('product','ProductsController');
    Route::resource('supplier','SuppliersController');
    Route::resource('order','OrdersController');
    Route::post('product/{id}/p-edit','ProductsController@updateproduct')->name('editproduct');
    Route::post('supplier/{id}/edit-supplier','SuppliersController@updatesupplier')->name('editsupplier');
    Route::post('order/{id}/edit-order','OrdersController@updateorder')->name('editorder');
});



//Auth user detail routes
Route::middleware('auth:api')->prefix('manage')->group(function(){
    Route::get('user','Auth\AuthController@auDetail')->name('auth_usr');
});
    
//Login and registration controllers
Route::post('register','Auth\AuthController@register')->name('signup');
Route::post('login','Auth\AuthController@login')->name('login');
