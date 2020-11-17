<?php
#Tickets
Route::group(['prefix' => 'Tickets'], function () {
    Route::get('threads', array('uses' => 'OS\Tickets\TicketsController@fetchThreads'));

    Route::get('thread/{id}', array('uses' => 'OS\Tickets\TicketsController@fetchThread'));

    Route::get('message/{id}', array('uses' => 'OS\Tickets\TicketsController@fetchMessage'));
});
