<?php
#Clients
$router->group(['middleware' => 'CheckPermission:client_permission'], function ($router) {
    Route::group(['prefix' => 'POS'], function () {
        Route::get('{client}/{products?}/{services?}', array('uses' => 'OS\POS\POSController@View'));
        Route::post('Save', array('uses' => 'OS\POS\POSController@Save'));
        Route::post('Swipe', array('uses' => 'OS\POS\POSController@Swipe'));
        Route::post('SwipeDecode', array('uses' => 'OS\POS\POSController@SwipeDecode'));
    });
});