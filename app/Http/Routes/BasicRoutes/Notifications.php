<?php
//$router->group(['middleware' => 'CheckPermission:chat_permission'], function ($router) {
Route::group(['prefix' => 'Notifications'], function () {
    Route::get('testcreate', 'OS\NotificationController@testcreate');
    Route::get('data', 'OS\NotificationController@fetchNotifications');
    #Route::get('threads', 'OS\NotificationController@fetchThreads');

    Route::get('notification/{id}', 'OS\NotificationController@fetchNotification');

    Route::post('New', 'OS\NotificationController@New');
    Route::post('NewAndEmail', 'OS\NotificationController@NewAndEmail');
    Route::post('PatList', 'OS\NotificationController@Patlist');

    Route::post('MarkRead', 'OS\NotificationController@MarkRead');
});
//});