<?php
Route::get('/Google/Auth', array('uses' => 'OS\GoogleController@GoogleAuth'));
Route::get('/Google/Popup/{location}', array('uses' => 'OS\GoogleController@SetupPopup'));
Route::post('/Google/DeleteToken', array('uses' => 'OS\GoogleController@DeleteToken'));
Route::get('/Google/PromptOff', array('uses' => 'OS\GoogleController@PromptOff'));
Route::post('/Google/Gmail/Send', array('uses' => 'OS\GoogleController@SendGmail'));