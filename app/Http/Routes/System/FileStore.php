<?php
#fileupload
Route::group(['prefix' => 'FileStore'], function () {
    Route::get('/', array('uses' => 'OS\FileStoreController@View'));
    //Route::get('ShowFile/{id}', array('uses' => 'OS\FileStoreController@ShowFile'));
    Route::post('Upload', array('uses' => 'OS\FileStoreController@Upload'));
    Route::post('CKEditor', array('uses' => 'OS\FileStoreController@UploadCKEditor'));
    Route::post('Description', array('uses' => 'OS\FileStoreController@Description'));
    Route::post('Delete', array('uses' => 'OS\FileStoreController@Delete'));
    Route::get('ViewerTest', array('uses' => 'OS\FileStoreController@ViewerTest'));
});