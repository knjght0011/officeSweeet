<?php
#Journal
$router->group(['middleware' => 'CheckPermission:journal_permission'], function ($router) {
    Route::group(['prefix' => 'Journal'], function () {
        Route::get('View', array('uses' => 'JournalController@showJournal'));
        Route::get('View/{month}/{year}', array('uses' => 'JournalController@showJournalWithDates'));
        Route::post('SaveComment', array('uses' => 'JournalController@SaveComment'));
        Route::group(['prefix' => 'MonthEnd'], function () {
            Route::get('Summery', array('uses' => 'MonthEndController@showSummery'));
            Route::get('Next', array('uses' => 'MonthEndController@showNext'));
            Route::get('ConfirmRecent', array('uses' => 'MonthEndController@ConfirmRecentBalance'));
            Route::get('UndoLast', array('uses' => 'MonthEndController@undoLast'));

            Route::post('First', array('uses' => 'MonthEndController@First'));
        });
    });
});