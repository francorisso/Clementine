<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
  return view('layout');
});

Route::controllers([
  'auth'      => 'Auth\AuthController',
  'password'  => 'Auth\PasswordController',
]);

Route::group([
  'prefix' => 'api',
  'namespace' => 'Api',
  'middleware' => 'auth'
],
function(){
  Route::resource('product', 'ProductController');
  Route::resource('order', 'OrderController');
  Route::resource('order-item', 'OrderItemController');
  Route::resource('provider', 'ProviderController');

  Route::get('createtoken', function () {
    return csrf_token();
  });
});