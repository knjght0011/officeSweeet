<?php
#Templates
$router->group(['middleware' => 'CheckPermission:templates_permission'], function ($router) {
    Route::group(['prefix' => 'Signing'], function () {
        Route::get('Setup/{id}', array('uses' => 'OS\Templates\SigningController@Setup'));
        Route::post('SavePositions', array('uses' => 'OS\Templates\SigningController@SavePositions'));

        Route::get('Approve/{id}', array('uses' => 'OS\Templates\SigningController@ApproveSignatures'));
        Route::post('SignatureImage', array('uses' => 'OS\Templates\SigningController@SignatureImage'));
        Route::post('Approve', array('uses' => 'OS\Templates\SigningController@Approve'));

        Route::get('', array('uses' => 'OS\Templates\SigningController@ShowSignTest'));

        Route::post('Email', array('uses' => 'OS\Templates\SigningController@EmailDocument'));

    });
});
