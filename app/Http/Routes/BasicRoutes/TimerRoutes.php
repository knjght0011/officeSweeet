<?php
Route::group(['prefix' => 'Timer'], function () {
    Route::get('', array('uses' => 'OS\TimerController@show'));
    Route::get('{id}', array('uses' => 'OS\TimerController@showWithClient'));

    Route::post('/Update', array('uses' => 'OS\Subscription\SubscriptionController@doUpdate'));
    Route::post('/Cancel', array('uses' => 'OS\Subscription\SubscriptionController@doCancel'));
});