<?php
$router->group(['middleware' => 'CheckPermission:client_permission'], function ($router) {
    Route::group(['prefix' => 'Clients'], function () {
        Route::group(['prefix' => 'Invoice'], function () {
            Route::get('View/{id}', array('uses' => 'InvoiceController@viewInvoice'));
            Route::get('PDFPage/{id}', array('uses' => 'InvoiceController@PDFPage'));
            Route::get('PDF/{id}', array('uses' => 'InvoiceController@showPdf'));
            Route::get('PDFhtml/{id}', array('uses' => 'InvoiceController@viewGeneratedInvoice'));
        });
    });
});