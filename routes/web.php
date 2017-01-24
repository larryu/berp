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

Route::get('/', function () {
    return view('home');
});

// show all orders
Route::get('orders', 'OrdersController@index');

// show all items
Route::get('items/{orderId}', 'ItemsController@index');

// show all products
Route::get('products', 'ProductsController@index');

Route::get('item/available', 'ItemsController@available');

Route::get('/emailnotify', function() {
    $order = App\Order::first();
    $product = App\Product::first();

    $order->notify(new \App\Notifications\ProductCreated($product));
});

Route::get('/products/view/{productId}', function() {
    return view('home');
});