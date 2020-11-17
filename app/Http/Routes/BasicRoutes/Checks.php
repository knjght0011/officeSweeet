<?php
#Check Writing
$router->group(['middleware' => 'CheckPermission:checks_permission'], function ($router) {
    Route::group(['prefix' => 'Checks'], function () {
        Route::get('PDF/{id}/{order?}', array('uses' => 'CheckController@showPdf'));
    });
});