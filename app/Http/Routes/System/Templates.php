<?php
#Templates
$router->group(['middleware' => 'CheckPermission:templates_permission'], function ($router) {
    Route::group(['prefix' => 'Templates'], function () {
        Route::get('List', array('uses' => 'OS\Templates\TemplateController@showTemplateList'));
        Route::post('Upload', array('uses' => 'OS\Templates\TemplateController@UploadTemplate'));
        Route::get('New', array('uses' => 'OS\Templates\TemplateController@showNewTemplate'));
        Route::get('Edit/{docID}', array('uses' => 'OS\Templates\TemplateController@EditTemplate'));
        Route::post('Save', array('uses' => 'OS\Templates\TemplateController@SaveTemplate'));
        Route::post('Delete', array('uses' => 'OS\Templates\TemplateController@deleteTemplate'));
    });
});
