<?php
Route::group(['prefix' => 'CustomTables'], function () {
    Route::get('/Tab/{tabid}/{dataid}', array('uses' => 'CustomTablesController@BuildTab'));
    Route::post('/Contents/Save', array('uses' => 'CustomTablesController@SaveSingleTab'));
    Route::get('/Get/{id}', array('uses' => 'CustomTablesController@GetID'));

});

$router->group(['middleware' => 'CheckPermission:acp_manage_custom_tables_permission'], function ($router) {
    Route::post('CustomTables/Save', array('uses' => 'CustomTablesController@SaveTable'));
    Route::post('CustomTables/Deactivate', array('uses' => 'CustomTablesController@DeactivateTable'));
    Route::post('CustomTables/Rename', array('uses' => 'CustomTablesController@RenameTable'));

});