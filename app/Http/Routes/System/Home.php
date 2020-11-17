<?php

Route::group(['prefix' => 'Home'], function () {

    Route::get('', array('uses' => 'OS\Home\HomeController@showHome'));

    Route::get('Clients', array('uses' => 'OS\Home\HomeController@showHomeClients'));
    Route::get('Patients', array('uses' => 'OS\Home\HomeController@showHomePatients'));
    Route::get('Prospects', array('uses' => 'OS\Home\HomeController@showHomeProspects'));
    Route::get('Vendors', array('uses' => 'OS\Home\HomeController@showHomeVendors'));
    Route::get('Employees', array('uses' => 'OS\Home\HomeController@showHomeEmployees'));

    Route::post('ColSave', array('uses' => 'OS\Home\HomeController@ColSave'));

});
