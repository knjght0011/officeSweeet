<?php
#Employees
$router->group(['middleware' => 'CheckPermission:employee_permission'], function ($router) {
    Route::group(['prefix' => 'Employees'], function () {

        Route::get('', array('uses' => 'EmployeesController@showMain'));

        Route::get('Add', array('uses' => 'EmployeesController@showAdd'));
        Route::get('Search', array('uses' => 'EmployeesController@showSearch'));
        Route::get('View/{id}/{page?}', array('uses' => 'EmployeesController@showView'));
        Route::get('Edit/{id}', array('uses' => 'EmployeesController@showEdit'));

        Route::post('Save', array('uses' => 'EmployeesController@SaveEmployee'));
        Route::post('SaveCompensation', array('uses' => 'EmployeesController@SaveEmployeeCompensation'));
        Route::post('SaveCommission', array('uses' => 'EmployeesController@SaveEmployeeCommission'));
        Route::post('Unlock', array('uses' => 'EmployeesController@Unlock'));

        Route::post('AddNote', array('uses' => 'EmployeesController@AddNote'));

        Route::post('Status', array('uses' => 'EmployeesController@Status'));


    });
});