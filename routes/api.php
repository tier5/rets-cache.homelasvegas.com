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

Route::group(array('prefix' => 'rets/v1'), function () {
    Route::resource('/', 'APIController');
    Route::get('homepage_listing', 'APIController@homepage_listing');
    Route::get('advance_search', 'APIController@advance_search');
    Route::get('property_desc/{matrix_unique_id}', 'APIController@property_desc');
    Route::get('photo_gallery/{matrix_unique_id}/{mls_number}', 'APIController@photo_gallery');
    Route::get('address_search/', 'APIController@addresssearch');
    Route::get('advance_listing/', 'APIController@advance_listing');
    Route::get('mortgage_cal/{matrix_unique_id}', 'APIController@mortgage_calculator');
    Route::get('printable_flyer/{matrix_unique_id}', 'APIController@printable_flyer');
    Route::get('test/{Matrix_Unique_ID}','APIController@thresholdCheck');
});

Route::group(['prefix' => 'rets/v2'],function (){
    Route::get('global_search',"ApiControllerV2@globalSearch");
    Route::get('rets_search',"ApiControllerV2@retsSearch");
    Route::get('advance_search','ApiControllerV2@advanceSearch');
    Route::get('address_search', 'ApiControllerV2@addressSearch');
    Route::get('advance_listing', 'ApiControllerV2@advanceListing');
    Route::get('view_more/{matrix_unique_id}','ApiControllerV2@viewMore');
    Route::get('photo_gallery/{matrix_unique_id}','ApiControllerV2@photoGallery');
    Route::get('mortgage_cal/{matrix_unique_id}', 'ApiControllerV2@mortgageCalculator');
    Route::get('printable_flyer/{matrix_unique_id}', 'ApiControllerV2@printableFlyer');
});
Route::group(['prefix' => 'rets/v3'],function (){
    Route::get('global_search',"ApiControllerV2@globalSearch");
    Route::get('rets_search',"ApiControllerV2@retsSearch");
    Route::get('advance_search','ApiControllerV2@advanceSearch');
    Route::get('address_search', 'ApiControllerV2@addressSearch');
    Route::get('advance_listing', 'ApiControllerV2@advanceListing');
    Route::get('view_more/{MLSNumber}','ApiControllerV3@viewMore');
    Route::get('photo_gallery/{MLSNumber}','ApiControllerV3@photoGallery');
    Route::get('mortgage_cal/{MLSNumber}', 'ApiControllerV3@mortgageCalculator');
    Route::get('printable_flyer/{MLSNumber}', 'ApiControllerV3@printableFlyer');
});
