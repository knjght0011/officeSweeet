<?php
#fileupload
Route::group(['prefix' => 'FileStore'], function () {
    Route::get('ShowFile/{id}', array('uses' => 'OS\FileStoreController@ShowFile'));
});