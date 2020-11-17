<?php
#Messages
$router->group(['middleware' => 'CheckPermission:chat_permission'], function ($router) {
    Route::group(['prefix' => 'messages'], function () {
        Route::get('unread', array('uses' => 'MessagingController@unread'));
        Route::get('/', ['as' => 'messages', 'uses' => 'MessagingController@index']);
        Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagingController@create']);
        Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagingController@store']);
        Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagingController@show']);
        Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagingController@update']);
    });
});