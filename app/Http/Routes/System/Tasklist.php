<?php
#Tasklist
$router->group(['middleware' => 'CheckPermission:tasks_permission'], function ($router) {
    Route::group(['prefix' => 'Tasklist'], function () {
        Route::get('', array('uses' => 'OS\TaskManagerController@ShowTasksMobile'));
        Route::post('Save', array('uses' => 'OS\TaskManagerController@SaveTask'));
        Route::post('MarkComplete', array('uses' => 'OS\TaskManagerController@MarkComplete'));
    });
});