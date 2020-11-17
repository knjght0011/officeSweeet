<?php
#Reciept entry/ now called expenses in syetem
$router->group(['middleware' => 'CheckPermission:reciepts_permission'], function ($router) {
    Route::group(['prefix' => 'Reciepts'], function () {
        Route::get('New/{userID}', array('uses' => 'RecieptController@ShowNewRecieptForm'));
        Route::get('Edit/{recieptID}', array('uses' => 'RecieptController@ShowEditRecieptForm'));
        Route::get('Attachment/{recieptID}', array('uses' => 'RecieptController@ShowAttachment'));
        Route::post('Save', array('uses' => 'RecieptController@Save'));
        Route::post('Delete', array('uses' => 'RecieptController@Delete'));

        Route::get('Test', array('uses' => 'RecieptController@Test'));


        Route::get('Convert', array('uses' => 'RecieptController@Convert'));
    });
});