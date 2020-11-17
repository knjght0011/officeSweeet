<?php
$router->group(['middleware' => 'CheckSubdomain:livedemo'], function ($router) {
    Route::get('login/{email}', array('uses' => 'OS\Users\LoginController@DemoLogin'));
    Route::get('DemoSignup', array('uses' => 'OS\Users\LoginController@DemoSignup'));
    Route::post('DemoSignup', array('uses' => 'OS\Users\LoginController@DemoSignupDo'));
});