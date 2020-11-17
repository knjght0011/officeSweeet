<?php
#Scheduling
$router->group(['middleware' => 'CheckPermission:scheduler_permission'], function ($router) {
    Route::group(['prefix' => 'Scheduling'], function () {
       Route::get('JsonFeed', array('uses' => 'SchedulingController@JsonFeed'));
    });
});

Route::group(['prefix' => 'Scheduling'], function () {
    Route::get('TrainingFeed', array('uses' => 'SchedulingController@TrainingFeed'));
});