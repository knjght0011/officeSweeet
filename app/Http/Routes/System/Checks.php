<?php
#Check Writing
$router->group(['middleware' => 'CheckPermission:checks_permission'], function ($router) {
    Route::group(['prefix' => 'Checks'], function () {
        Route::get('New/{type?}/{dataID?}', array('uses' => 'CheckController@ShowNewCheckForm'));
        Route::get('Edit/{id}', array('uses' => 'CheckController@ShowEditCheckForm'));
        Route::post('Delete', array('uses' => 'CheckController@DeleteCheck'));

        //Route::get('PDF/{id}/{order?}', array('uses' => 'CheckController@showPdf'));

        Route::get('Queue', array('uses' => 'CheckController@showCheckQueue'));
        Route::post('Queue/Save', array('uses' => 'CheckController@SaveCheckToQueue'));

        Route::get('Printed/{id}/{checkno}', array('uses' => 'CheckController@CheckPrinted'));

        #Route::get('View/{id}', array('uses' => 'InvoiceController@viewInvoice'));
        #Route::get('PDF/{id}', array('uses' => 'InvoiceController@showPdf'));
    });
});