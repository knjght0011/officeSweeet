<?php
#Scheduling
$router->group(['middleware' => 'CheckPermission:scheduler_permission'], function ($router) {
    Route::group(['prefix' => 'Scheduling'], function () {
        Route::get('View/{userid?}', array('uses' => 'SchedulingController@ShowSchedule'));
        Route::get('View/{type}/{date}', array('uses' => 'SchedulingController@ShowScheduleDay'));
        #type can be 'month', 'agendaWeek', 'timelineThreeDays', 'timelineDay'
        #date is a unix timestamp

        //Route::get('JsonFeed', array('uses' => 'SchedulingController@JsonFeed'));

        Route::post('SaveView', array('uses' => 'SchedulingController@SaveView'));

        Route::post('Save', array('uses' => 'SchedulingController@SaveEvent'));
        Route::post('Delete', array('uses' => 'SchedulingController@DeleteEvent'));
        Route::post('Notify', array('uses' => 'SchedulingController@Notify'));

        Route::post('DragableEvents/Save', array('uses' => 'SchedulingController@saveSchedulerEvent'));
        Route::post('DragableEvents/Delete', array('uses' => 'SchedulingController@deleteSchedulerEvent'));
        Route::post('DragableEvents/Duration', array('uses' => 'SchedulingController@DragableDuration'));

        Route::post('filter', array('uses' => 'SchedulingController@saveFilter'));
        Route::post('filterReset', array('uses' => 'SchedulingController@resetFilter'));

        Route::get('Json', array('uses' => 'SchedulingController@GetScheduleJSON'));
    });
});