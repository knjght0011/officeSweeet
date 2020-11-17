<?php
#Clients
$router->group(['middleware' => 'CheckPermission:client_permission'], function ($router) {
    Route::group(['prefix' => 'Prospects'], function () {

        Route::get('', array('uses' => 'ClientsController@showMainProspects'));
        Route::get('Json', array('uses' => 'ClientsController@GetProspectJSON'));
    });

    Route::group(['prefix' => 'Clients'],  function () {

        Route::get('Json', array('uses' => 'ClientsController@GetClientJSON'));

        Route::get('', array('uses' => 'ClientsController@showMain'));


        Route::get('Add', array('uses' => 'ClientsController@showAdd'));

        Route::post('AddBillableHours', array('uses' => 'ClientsController@SaveBillableHours'));

        Route::get('Search', array('uses' => 'ClientsController@showSearch'));
        Route::get('View/{id}/{tab?}', array('uses' => 'ClientsController@showView'));
        Route::get('Edit/{id}', array('uses' => 'ClientsController@showEdit'));

        Route::post('Update/Details', array('uses' => 'ClientsController@UpdateDetails'));

        Route::post('Save', array('uses' => 'ClientsController@UpdateClient'));

        Route::post('Disable', array('uses' => 'ClientsController@DisableClient'));

        Route::post('AddClient', array('uses' => 'ClientsController@CreateNewClient'));
        Route::post('AddNote', array('uses' => 'ClientsController@AddNote'));

        Route::get('ResendEmail/{accountid}/{email?}', array('uses' => 'ClientsController@ResendWelcomeEmail'));

        Route::group(['prefix' => 'Contact'], function () {
            Route::get('{id}', array('uses' => 'ClientsController@showContact'));
            Route::get('New/{id}', array('uses' => 'ClientsController@newContact'));
            Route::post('Disable', array('uses' => 'ClientsController@DisableContact'));
            Route::post('Save', array('uses' => 'ClientsController@SaveContact'));
            Route::post('ChangePrimary', array('uses' => 'ClientsController@ChangePrimary'));
        });

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
    });

    Route::group(['prefix' => 'Patients'], function () {
        Route::post('Save', array('uses' => 'OS\Clients\PatientsController@Save'));
        Route::post('Schedule', array('uses' => 'OS\Clients\PatientsController@Schedule'));
        Route::get('View', array('uses' => 'OS\Clients\PatientsController@View'));
    });
});