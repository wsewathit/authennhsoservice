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

Route::prefix('coreapi')->group(function() {
	Route::get('/', 'CoreapiController@index');
	Route::group([ 'prefix' => 'api/v1'], function(){
		Route::get('get_patient/{id}', 'V1\ApiV1Controller@get_patient');
		Route::post('post_authen', 'V1\ApiV1Controller@post_authen');
	});
	Route::get('/print_queue/{id}', 'V1\ApiV1Controller@print_queue');
});
