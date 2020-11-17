<?php
#Vendors
$router->group(['middleware' => 'CheckPermission:vendor_permission'], function ($router) {
    Route::group(['prefix' => 'Vendors'], function () {
        #pages
        Route::get('', array('uses' => 'VendorsController@showMain'));

        Route::get('Json', array('uses' => 'VendorsController@GetVendorJSON'));

        Route::get('Add', array('uses' => 'VendorsController@showAdd'));
        Route::get('Search', array('uses' => 'VendorsController@showSearch'));
        Route::get('View/{id}/{tab?}', array('uses' => 'VendorsController@showView'));
        Route::get('Edit/{id}', array('uses' => 'VendorsController@showEdit'));

        Route::post('Save', array('uses' => 'VendorsController@UpdateVendor'));

        Route::post('Disable', array('uses' => 'VendorsController@DisableVendor'));


        #inputs
        Route::post('New', array('uses' => 'VendorsController@CreateNewVendor'));
        Route::post('AddNote', array('uses' => 'VendorsController@AddNote'));

        Route::group(['prefix' => 'Contact'], function () {
            Route::get('{id}', array('uses' => 'VendorsController@showContact'));
            Route::get('New/{id}', array('uses' => 'VendorsController@newContact'));
            Route::post('Disable', array('uses' => 'VendorsController@DisableContact'));
            Route::post('Save', array('uses' => 'VendorsController@SaveContact'));
            Route::post('ChangePrimary', array('uses' => 'VendorsController@ChangePrimary'));
        });
    });
});