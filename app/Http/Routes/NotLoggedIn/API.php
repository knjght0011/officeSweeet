<?php
Route::group(['prefix' => 'API'], function () {

    Route::group(['prefix' => 'Permissions'], function () {

        Route::post('/', array('uses' => 'OS\API\APIController@MyPermissions'));

    });

    Route::group(['prefix' => 'Notification'], function () {
        Route::post('All', array('uses' => 'OS\API\APIController@MyNotifications'));
        Route::post('', array('uses' => 'OS\API\APIController@GetNotification'));
        Route::post('Since', array('uses' => 'OS\API\APIController@MyNotificationsSince'));
        Route::post('MarkRead', array('uses' => 'OS\API\APIController@MarkNotificationRead'));
    });

    Route::group(['prefix' => 'Users'], function () {
        Route::post('CanLogin', array('uses' => 'OS\API\UserAPIController@CanLogin'));
    });

    Route::group(['prefix' => 'Chat'], function () {
        Route::post('Thread', array('uses' => 'OS\API\ChatAPIController@ChatThread'));
        Route::post('MarkThreadRead', array('uses' => 'OS\API\ChatAPIController@MarkThreadRead'));
        Route::post('CreateThread', array('uses' => 'OS\API\ChatAPIController@CreateThread'));
        Route::post('Threads', array('uses' => 'OS\API\ChatAPIController@ChatThreads'));
        Route::post('Message', array('uses' => 'OS\API\ChatAPIController@ChatMessage'));
    });

    Route::get('SupportVideos', array('uses' => 'OS\API\APIController@SupportVideos'));


});