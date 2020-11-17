<?php
#Payroll
$router->group(['middleware' => 'CheckPermission:payroll_permission'], function ($router) {
    Route::group(['prefix' => 'Payroll'], function () {
        Route::get('/', array('uses' => 'OS\Financial\PayrollController@showPayroll'));
        //Route::get('Report/{id}', array('uses' => 'OS\Financial\PayrollController@showReport'));

        Route::post('Finalise', array('uses' => 'OS\Financial\PayrollController@Finalise'));
        Route::post('SaveEmployeePayroll', array('uses' => 'OS\Financial\PayrollController@SaveEmployeePayroll'));
        Route::post('DeleteRow', array('uses' => 'OS\Financial\PayrollController@DeleteRow'));
        Route::post('Setup', array('uses' => 'OS\Financial\PayrollController@Setup'));
        Route::post('Setup2', array('uses' => 'OS\Financial\PayrollController@Setup2'));
    });
});