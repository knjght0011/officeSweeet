<?php
#users
$router->group(['middleware' => 'CheckPermission:login_management_permission'], function ($router) {

    Route::get('Users/All', array('uses' => 'UserController@Data'));

    Route::post('Users/Save/Email', array('uses' => 'UserController@SaveEmail'));
    Route::post('Users/Save/Branch', array('uses' => 'UserController@SaveBranch'));
    Route::post('Users/Save/Permissions', array('uses' => 'UserController@SavePermissions'));

    #In Use in new employee's screen
    Route::post('Users/EnableLogin', array('uses' => 'UserController@EnableLogin'));
    Route::post('Users/DisableLogin', array('uses' => 'UserController@DisableLogin'));

    Route::post('Users/Save/EnablePermission', array('uses' => 'UserController@EnablePermission'));
    Route::post('Users/Save/DisablePermission', array('uses' => 'UserController@DisablePermission'));
    Route::post('Users/Save/SetPermission', array('uses' => 'UserController@SetPermission'));

    Route::post('Users/Save/ToggleLoginStatus', array('uses' => 'UserController@ToggleLoginStatus'));
    Route::post('Users/Save/Password', array('uses' => 'UserController@SavePassword'));

    #shouldnt be needed anymore
    #Route::post('Users/Save/NewUser', array('uses' => 'UserController@NewUser'));

});