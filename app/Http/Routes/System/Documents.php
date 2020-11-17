<?php
Route::group(['prefix' => 'Documents'], function () {
    Route::get('List/{type}/{dataID}', array('uses' => 'OS\Templates\DocXController@showTemplateList'));
    Route::get('Generate/{tempID}/{dataID}', array('uses' => 'OS\Templates\DocXController@generateDoc'));
    Route::get('Edit/{docID}', array('uses' => 'OS\Templates\DocXController@EditDocument'));
    Route::post('Save', array('uses' => 'OS\Templates\DocXController@SaveDoc'));
    //Route::get('PDF/{docID}', array('uses' => 'OS\Templates\DocXController@DownloadPDF'));
    Route::get('Test', array('uses' => 'OS\Templates\DocXController@MigrateOldTables'));
});