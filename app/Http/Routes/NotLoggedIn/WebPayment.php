<?php

Route::group(['prefix' => 'WebPayment'], function () {
    Route::get('View', array('uses' => 'WebPaymentsController@ShowPaymentPage'));
    Route::post('Send', array('uses' => 'WebPaymentsController@Swipe'));
});

