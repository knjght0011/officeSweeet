<?php

Route::group(['prefix' => 'test'], function () {
    Route::get('email', array('uses' => 'TestingController@ShowTestingPage'));
    Route::post('SendEmail', array('uses' => 'TestingController@SendTestEmail'));
});

