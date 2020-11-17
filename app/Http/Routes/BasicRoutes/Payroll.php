<?php
#Payroll
$router->group(['middleware' => 'CheckPermission:payroll_permission'], function ($router) {
    Route::group(['prefix' => 'Payroll'], function () {
        Route::get('Report/{id}', array('uses' => 'OS\Financial\PayrollController@showReport'));
    });
});