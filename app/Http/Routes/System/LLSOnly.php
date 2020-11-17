<?php
$router->group(['middleware' => 'CheckSubdomain:lls'], function ($router) {

    Route::get('AddMigrationsToQueue', array('uses' => 'Management\CronController@AddMigrationsToQueue'));

    Route::group(['prefix' => 'Plans'], function () {

        Route::get('', array('uses' => 'Management\PlanController@showOverview'));
        Route::post('Edit', array('uses' => 'Management\PlanController@editPlan'));
        Route::post('Add', array('uses' => 'Management\PlanController@addPlan'));
    });

    Route::group(['prefix' => 'LLSNEWSignup'], function () {

        Route::get('{clientid}', array('uses' => 'Signup\SignupController@showLLSSignup'));

        Route::post('Subscribe', array('uses' => 'Signup\SignupController@doLLSSignup'));
        Route::post('SubdomainCheck', array('uses' => 'Signup\SignupController@SubdomainCheck'));

    });

    Route::group(['prefix' => 'Promotions'], function () {
        Route::post('Add', array('uses' => 'Promotions\PromotionController@addPromotion'));
    });

    Route::get('TNrefresh', array('uses' => 'Management\CronController@TNrefresh'));

    Route::group(['prefix' => 'Alerts'], function () {
        Route::get('/', array('uses' => 'Management\AlertController@viewAlerts'));
        Route::get('/{id}', array('uses' => 'Management\AlertController@viewAlert'));
        Route::get('/Mark/{id}', array('uses' => 'Management\AlertController@mark'));
    });

    Route::resource('Accounts', 'Management\AccountController');

    Route::post('Account/SetActiveDate', array('uses' => 'Management\AccountController@SetActiveDate'));
    Route::post('Account/SetUsers', array('uses' => 'Management\AccountController@SetUsers'));

    Route::get('Accounts/migrate/{subdomain}', array('uses' => 'Management\AccountController@migrate'));
    Route::get('Accounts/rollback/{subdomain}', array('uses' => 'Management\AccountController@rollback'));

    Route::get('TestTaskQueue/{id}', array('uses' => 'Management\CronController@TestTaskQueue'));

    Route::get('Test123', array('uses' => 'Management\CronController@Test'));

    Route::group(['prefix' => 'QuickBooksTesting'], function () {

        Route::get('CreateInvoice', function (){
            \App\Helpers\OS\QuickBooks\QuickBooksHelper::CreateInvoice();
        });

        Route::get('CreateCustomer', function (){
            \App\Helpers\OS\QuickBooks\QuickBooksHelper::CreateCustomer();
        });

        Route::get('GetCustomerById', function (){
            \App\Helpers\OS\QuickBooks\QuickBooksHelper::GetCustomerById();
        });
    });


});