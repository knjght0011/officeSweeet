<?php
Route::group(['prefix' => 'Documents'], function () {
    Route::get('PDF/{docID}', array('uses' => 'OS\Templates\DocXController@DownloadPDF'));
});