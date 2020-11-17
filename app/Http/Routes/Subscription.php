<?php
Route::get('AccountDisabled', array('uses' => 'OS\Subscription\SubscriptionController@AccountDisabled'));
Route::post('Subscribe', array('uses' => 'OS\Subscription\SubscriptionController@doSubscription'));

#subscription management
$router->group(['middleware' => 'CheckPermission:acp_subscription_permission'], function ($router) {
    Route::group(['prefix' => 'Subscription'], function () {
        Route::get('', array('uses' => 'OS\Subscription\SubscriptionController@showSummery'));
        Route::get('Signup', array('uses' => 'OS\Subscription\SubscriptionController@SignUp'));

        Route::post('/Update', array('uses' => 'OS\Subscription\SubscriptionController@doUpdate'));
        Route::post('/Cancel', array('uses' => 'OS\Subscription\SubscriptionController@doCancel'));
    });
});