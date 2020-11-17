<?php
#Tickets
Route::group(['prefix' => 'Tickets'], function () {

    Route::get('', array('uses' => 'OS\Tickets\TicketsController@index'));
    //Route::get('threads', array('uses' => 'OS\Tickets\TicketsController@fetchThreads'));
    Route::post('NewThread', array('uses' => 'OS\Tickets\TicketsController@NewThread'));
    Route::post('sendMessage', array('uses' => 'OS\Tickets\TicketsController@sendMessage'));
    Route::post('updateStatus', array('uses' => 'OS\Tickets\TicketsController@updateStatus'));

});
