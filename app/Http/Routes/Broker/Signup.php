<?php
#Clients
$router->group(['middleware' => 'CheckPermission:client_permission'], function ($router) {
    Route::group(['prefix' => 'Signup'], function () {

        Route::get('{clientid}', array('uses' => 'Signup\SignupController@showBrokerSignup'));

        Route::post('Subscribe', array('uses' => 'Signup\SignupController@doBrokerSignup'));
        Route::post('SubdomainCheck', array('uses' => 'Signup\SignupController@SubdomainCheck'));

    });
});