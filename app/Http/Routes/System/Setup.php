<?php
Route::group(['prefix' => 'Setup'], function () {
    Route::get('', array('uses' => 'OS\SetupController@ShowSetup'));
    Route::post('', array('uses' => 'OS\SetupController@SaveData'));
});