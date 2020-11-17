<?php
#Clients
$router->group(['middleware' => 'CheckPermission:vendor_permission'], function ($router) {
    Route::group(['prefix' => 'PurchaseOrders'], function () {
        Route::get('', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@Overview'));
        Route::get('Status/{id}', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@Status'));

        Route::post('UpdateStatus', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@UpdateStatus'));
        Route::post('MarkReceived', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@MarkReceived'));

        Route::get('New/{id}', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@new'));
        Route::get('Edit/{id}', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@edit'));
        //Route::get('PDF/{id}', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@PDF'));

        Route::post('Save', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@Save'));
    });
});