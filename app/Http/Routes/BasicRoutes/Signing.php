<?php
#Templates
$router->group(['middleware' => 'CheckPermission:templates_permission'], function ($router) {
    Route::group(['prefix' => 'Signing'], function () {
        Route::get('PDF/{id}', array('uses' => 'OS\Templates\SigningController@ShowSignPDF'));
    });
});
