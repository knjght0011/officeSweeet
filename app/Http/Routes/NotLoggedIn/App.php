<?php
#chat

Route::group(['prefix' => 'App'], function () {

    Route::get('SubCheck', function (){ return ['status' => 'OK']; });

    Route::post('LoginCheck', 'OS\App\AppController@CheckLogin');

});