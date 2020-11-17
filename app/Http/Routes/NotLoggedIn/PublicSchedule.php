<?php

Route::group(['prefix' => 'PublicSchedule'], function () {
    Route::get('View', array('uses' => 'PublicSchedulingController@ShowSchedule'));
    Route::get('View/{type}/{date}', array('uses' => 'PublicSchedulingController@ShowScheduleDay'));

    Route::get('JsonFeed', array('uses' => 'PublicSchedulingController@JsonFeed'));
    Route::get('TrainingFeed', array('uses' => 'PublicSchedulingController@TrainingFeed'));

    Route::get('ical', array('uses' => 'PublicSchedulingController@ical'));
});
