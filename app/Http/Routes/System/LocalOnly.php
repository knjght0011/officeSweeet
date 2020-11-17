<?php
$router->group(['middleware' => 'CheckSubdomain:local'], function ($router) {
/*
    Route::group(['prefix' => 'SignupLLS'], function () {

        Route::get('{clientid}', array('uses' => 'Signup\SignupController@showLLSSignup'));

        Route::post('Subscribe', array('uses' => 'Signup\SignupController@doLLSSignup'));
        Route::post('SubdomainCheck', array('uses' => 'Signup\SignupController@SubdomainCheck'));

    });

    Route::group(['prefix' => 'Promotions'], function () {

        Route::post('Add', array('uses' => 'Promotions\PromotionController@addPromotion'));

    });
*/

    Route::get('CatagorysModal', array('uses' => 'OS\TestController@CatagorysModal'));

    Route::group(['prefix' => 'Logs'], function () {
        Route::get('Search/{subdomain}', array('uses' => 'Management\LogController@ShowSearch'));
        Route::get('View/{subdomain}/{ID}', array('uses' => 'Management\LogController@ShowView'));
        Route::get('Exception/{subdomain}/{ID}', array('uses' => 'Management\LogController@showException'));
    });

    Route::group(['prefix' => 'Testing'], function () {
        Route::get('/TransnationalPostAPI', array('uses' => 'Management\TestingController@TestPayment'));
        Route::get('/TransactionID/{id}', array('uses' => 'Management\TestingController@TransnationalTests'));
        Route::get('/DeleteAccount/{id}', array('uses' => 'Management\TestingController@DeleteAccount'));
    });

    Route::get('Blank', array('uses' => 'Management\TestingController@Blank'));

    //Route::get('Test', array('uses' => 'OS\GoogleController@Test'));

    //Route::get('AddMigrationsToQueue', array('uses' => 'Management\CronController@AddMigrationsToQueue'));

    Route::get('AddRollbackToQueue', array('uses' => 'Management\CronController@AddRollbackToQueue'));

    #Route::get('TestTaskQueue/{id}', array('uses' => 'Management\CronController@TestTaskQueue'));

    Route::get('Test', array('uses' => 'Management\CronController@Test'));

    Route::get('TestAddingToLLS', function (){


        $string = 'Lorem ipsum ...@a.com, bb12@bb12.com dolor sit abc-abc@abc.edu.co.uk amet';

        preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches, PREG_OFFSET_CAPTURE);

        var_dump($matches[0]);

        $startint = 0;
        $output = "";
        foreach($matches[0] as $match){

            $start = substr($string, $startint, $match[1] - $startint);

            $explode = explode ( "@" , $match[0]);
            $end =  "...@" . $explode[1];

            $output = $output . $start . $end;

            $startint = $startint + $match[1] + 12;
            var_dump($startint);
        }


        $output = $output . substr($string, end($matches[0])[1] + 12);

        return $output;

    });



    Route::get('MailGunTest', function (){
        \App\Helpers\OS\Mailgun\MailgunHelper::SendEmail();
    });
});
