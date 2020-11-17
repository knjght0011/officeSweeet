<?php
#Reporting
$router->group(['middleware' => 'CheckPermission:reporting_permission'], function ($router) {
    Route::group(['prefix' => 'Reporting'], function () {
        Route::get('/', array('uses' => 'ReportingController@ShowReportsPage'));
        //Route::get('/Report/{reporttype}/{date}/{timeframe}/{option}/{output}', array('uses' => 'ReportingController@ShowReport'));

        #Route::get('TestPalatte', array('uses' => 'ReportingController@TestPalatte'));


        Route::group(['prefix' => 'Interactive'], function () {
            Route::get('/Quotes', array('uses' => 'ReportingController@ShowQuoteReport'));
            Route::get('/Invoices', array('uses' => 'ReportingController@ShowInvoiceReport'));
            Route::get('/ProfitAndLoss/{start?}/{end?}', array('uses' => 'ReportingController@ShowInteractiveProfitAndLossReport'));
        });
    });
});