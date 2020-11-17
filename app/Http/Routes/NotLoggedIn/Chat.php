<?php
#chat

Route::group(['prefix' => 'Chat'], function () {

    //Route::get('messages', 'OS\ChatsController@fetchMessages');
    Route::get('threads/{email}/{password}', 'OS\ChatsController@fetchThreadsApp');
    //Route::get('thread/{id}', 'OS\ChatsController@fetchThread');

    Route::post('messagesApp', 'OS\ChatsController@sendMessageApp');
    /**
    Route::post('thread', 'OS\ChatsController@NewThread');
    Route::post('thread/adduser', 'OS\ChatsController@AddUserThread');
    Route::post('thread/removeuser', 'OS\ChatsController@RemoveUserThread');
    Route::post('thread/rename', 'OS\ChatsController@RenameThread');
    Route::post('thread/markread', 'OS\ChatsController@MarkReadThread');
     */
});