<?php
// route to show & Process the login form
Route::get('login', array('uses' => 'OS\Users\LoginController@showLogin'));
Route::get('loginPOST', function (){return['status' => 'notlogedin'];});
Route::post('login', array('uses' => 'OS\Users\LoginController@doLogin'));
Route::post('SuperSecretAppLogin', array('uses' => 'OS\Users\LoginController@doLogin'));
Route::get('logout', array('uses' => 'OS\Users\LoginController@doLogout'));