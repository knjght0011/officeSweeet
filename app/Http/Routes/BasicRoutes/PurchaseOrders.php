<?php
#Clients
$router->group(['middleware' => 'CheckPermission:vendor_permission'], function ($router) {
    Route::group(['prefix' => 'PurchaseOrders'], function () {
        Route::get('PDF/{id}', array('uses' => 'OS\PurchaseOrders\PurchaseOrdersController@PDF'));
    });
});