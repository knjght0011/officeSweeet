<?php
#chat
$router->group(['middleware' => 'CheckPermission:chat_permission'], function ($router) {
    Route::group(['prefix' => 'Chat'], function () {
        Route::get('/', 'OS\ChatsController@index');

        Route::get('messages', 'OS\ChatsController@fetchMessages');
        Route::get('message/{id}', 'OS\ChatsController@fetchMessage');

        Route::get('threads', 'OS\ChatsController@fetchThreads');
        Route::get('thread/{id}', 'OS\ChatsController@fetchThread');

        Route::post('messages', 'OS\ChatsController@sendMessage');
        Route::post('thread', 'OS\ChatsController@NewThread');

        Route::post('thread/markread', 'OS\ChatsController@MarkReadThread');
        Route::post('thread/update', 'OS\ChatsController@UpdateThread');

        //Route::post('thread/adduser', 'OS\ChatsController@AddUserThread');
        //Route::post('thread/removeuser', 'OS\ChatsController@RemoveUserThread');
        //Route::post('thread/rename', 'OS\ChatsController@RenameThread');

    });
});