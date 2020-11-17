<?php
#Reporting
$router->group(['middleware' => 'CheckPermission:reporting_permission'], function ($router) {
    Route::group(['prefix' => 'Reporting'], function () {
        Route::get('/Report/{reporttype}/{date}/{timeframe}/{option}/{output}', array('uses' => 'ReportingController@ShowReport'));
    });
});