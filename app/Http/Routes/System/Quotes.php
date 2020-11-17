<?php
#Clients
$router->group(['middleware' => 'CheckPermission:client_permission'], function ($router) {

    Route::group(['prefix' => 'Quote'], function () {
        Route::get('New/{id}', array('uses' => 'OS\Quotes\QuoteController@New'));
        Route::get('Edit/{id}', array('uses' => 'OS\Quotes\QuoteController@Edit'));
        Route::post('Save', array('uses' => 'OS\Quotes\QuoteController@SaveQuote'));
        Route::post('Delete', array('uses' => 'QuoteController@Delete'));

        Route::get('Final/{id}', array('uses' => 'OS\Quotes\QuoteController@ShowFinal'));
        Route::post('Final', array('uses' => 'OS\Quotes\QuoteController@DoFinal'));

        Route::get('PDF/{id}', array('uses' => 'OS\Quotes\QuoteController@showPdf'));
    });

    /*
    Route::group(['prefix' => 'Quote'], function () {
        Route::get('New/{id}', array('uses' => 'QuoteController@newQuote'));
        Route::get('Edit/{id}', array('uses' => 'QuoteController@editQuote'));
        Route::post('Save', array('uses' => 'QuoteController@SaveQuote'));
        Route::post('Delete', array('uses' => 'QuoteController@Delete'));

        Route::get('Finalize/{id}', array('uses' => 'QuoteController@ShowFinalize'));
        Route::post('Finalize', array('uses' => 'QuoteController@FinalizeQuote'));
    });

    Route::group(['prefix' => 'Invoice'], function () {
        Route::post('Payment', array('uses' => 'InvoiceController@AddPayment'));
        //Route::get('View/{id}', array('uses' => 'InvoiceController@viewInvoice'));
        //Route::get('PDFPage/{id}', array('uses' => 'InvoiceController@PDFPage'));
        //Route::get('PDF/{id}', array('uses' => 'InvoiceController@showPdf'));
        //Route::get('PDFhtml/{id}', array('uses' => 'InvoiceController@viewGeneratedInvoice'));
        Route::post('Delete', array('uses' => 'InvoiceController@Delete'));

        Route::post('RecurringStop', array('uses' => 'InvoiceController@RecurringStop'));
    });
    */
});