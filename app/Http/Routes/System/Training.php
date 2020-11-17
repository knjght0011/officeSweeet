<?php
#Deposits
#$router->group(['middleware' => 'CheckPermission:deposits_permission'], function ($router) {
    Route::group(['prefix' => 'Training'], function () {
        Route::get('/', array('uses' => 'OS\Training\TrainingController@index'));
        Route::get('/{department}/{training}', array('uses' => 'OS\Training\TrainingController@DepartmentSearch'));
        #Route::get('Edit/{dataID}', array('uses' => 'DepositController@EditDeposit'));
        #Route::post('Delete', array('uses' => 'DepositController@Delete'));


        Route::post('AddBulk', array('uses' => 'OS\Training\TrainingController@addTrainingBulk'));

        Route::post('Add', array('uses' => 'OS\Training\TrainingController@addTraining'));
        Route::post('Complete', array('uses' => 'OS\Training\TrainingController@completeTraining'));
        Route::post('Delete', array('uses' => 'OS\Training\TrainingController@deleteTraining'));
        Route::post('Edit', array('uses' => 'OS\Training\TrainingController@editTraining'));

    });
#});