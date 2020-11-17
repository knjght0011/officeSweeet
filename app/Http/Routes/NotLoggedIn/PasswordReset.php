<?php
Route::get('resetrequest', array('uses' => 'OS\Users\PasswordController@showResetRequest'));
Route::post('resetrequest', array('uses' => 'OS\Users\PasswordController@RequestReset'));
Route::get('reset/{id}', array('uses' => 'OS\Users\PasswordController@showReset'));
Route::post('reset', array('uses' => 'OS\Users\PasswordController@Reset'));
Route::get('email', array('uses' => 'OS\Users\PasswordController@showResetRequestEmail'));

