<?php
#Address
Route::group(['prefix' => 'Address'], function () {
    Route::post('Add', array('uses' => 'AddressController@doAdd'));
    Route::post('Lookup', array('uses' => 'AddressController@LookupAddress'));
});
