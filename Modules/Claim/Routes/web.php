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

Route::prefix('claim')->group(function() {
	Route::group(['prefix'=>'frontendclaim', 'as'=>'frontendclaim.'],function() { 
		Route::get('/', ['as'=>'frontendclaim','uses'=>'FrontendclaimController@index']);
	});
	Route::group(['prefix'=>'authenclaim', 'as'=>'authenclaim.'],function() { 
		Route::get('/', ['as'=>'authenclaim','uses'=>'AuthenController@index']);
	});


    Route::get('/', 'ClaimController@index');
    
    Route::get('/generate_text', 'ClaimController@generate_text');
    Route::get('/get_data/{id}', 'ClaimGenApiController@GenerateData');
    Route::get('/curl_api_eclaim/{id}', 'ClaimGenApiController@CurlApiEclaim');


    Route::group(['prefix'=>'opd', 'as'=>'opdclaim.'],function() { 

    	Route::get('/generate_text', 'ClaimOpdController@generate_text');
 	  	Route::get('/get_data/{id}', 'ClaimOpdController@GenerateData');
        Route::get('/curl_api_eclaim/{id}', 'ClaimOpdController@CurlApiEclaim');
    });
  



    
});
