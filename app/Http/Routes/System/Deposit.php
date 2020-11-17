<?php
#Deposits
$router->group(['middleware' => 'CheckPermission:deposits_permission'], function ($router) {
    Route::group(['prefix' => 'Deposit'], function () {
        Route::get('Edit/{dataID}', array('uses' => 'DepositController@EditDeposit'));
        Route::post('Delete', array('uses' => 'DepositController@Delete'));
        Route::post('Save', array('uses' => 'DepositController@Save'));
        Route::post('Add/Misc', array('uses' => 'DepositController@AddMiscDeposit'));
        Route::post('Add/Client', array('uses' => 'DepositController@AddClientDeposit'));
        Route::post('Add/Invoice', array('uses' => 'DepositController@AddInvoiceDeposit'));
    });
});