<?php
#User Accounts
Route::group(['prefix' => 'Account'], function () {
    Route::get('/', array('uses' => 'AccountController@showAccountPage'));
    Route::get('/Clock/In', array('uses' => 'AccountController@ClockIn'));
    Route::get('/Clock/Out', array('uses' => 'AccountController@ClockOut'));
    Route::post('/Clock/Update', array('uses' => 'AccountController@ClockUpdate'));

    Route::post('Timezone/Save', array('uses' => 'AccountController@saveTimezone'));

    Route::post('/Password', array('uses' => 'AccountController@SavePassword'));

    Route::post('/Option', array('uses' => 'AccountController@SetOption'));

    Route::post('ToggleScheduleEmail', array('uses' => 'AccountController@ToggleScheduleEmail'));
});
