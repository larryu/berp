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

// api for importing order from third party
Route::post('orders', 'APIOrdersController@import');

// api for getting all orders
Route::get('orders', 'APIOrdersController@index');

// api for getting item
Route::get('item/{itemId}', 'APIItemsController@show');

Route::get('items/{orderId}', 'APIItemsController@index');

Route::post('item/create', 'APIItemsController@store');
Route::post('item/{itemId}', 'APIItemsController@update');

// api for getting all orders
Route::get('products', 'APIProductsController@index');
Route::get('product/{productId}', 'APIProductsController@show');
Route::post('products/create', "APIProductsController@store");
Route::post('products/edit/{productId}', "APIProductsController@update");

Route::get('available/items', 'APIItemsController@available');
Route::post('orders/items/add/', 'APIItemsController@add');
Route::post('orders/items/delete/','APIItemsController@delete' );