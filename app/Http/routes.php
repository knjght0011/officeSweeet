<?php

Route::get('cron', array('uses' => 'Management\CronController@CronHandler'));

if (env('APP_ENV') === "local") {
    #$signup = ['domain' => 'signuptest.officesweeet.com'];
    $promotions = ['domain' => 'promotionstest.officesweeet.com'];
    $google = ['domain' => 'google.officesweeet.com'];
    $api = ['domain' => 'apitest.officesweeet.com'];
} else {
    #$signup = ['domain' => 'signup.officesweeet.com'];
    $promotions = ['domain' => 'promotions.officesweeet.com'];
    $google = ['domain' => 'google.officesweeet.com'];
    $api = ['domain' => 'api.officesweeet.com'];
}

$router->group($api, function ($router) {
    #Route::post('/signup', array('uses' => 'Signup\SignupController@DoSignup'));

    Route::post('/solosignup', array('uses' => 'Signup\SignupController@DoSoloSignup'));

    Route::post('/signupnew', array('uses' => 'Signup\SignupController@DoNewSignup'));

    Route::get('/Verify/{token}', array('uses' => 'Signup\SignupController@VerifySignup'));

    Route::get('/VerifyTest/', array('uses' => 'Signup\SignupController@VerifyTest'));

    Route::get('/Provisionmigrationsing/{subdomain}', array('uses' => 'Signup\SignupController@Provisioning'));

    Route::get('/ProvisioningCheck/{subdomain}', array('uses' => 'Signup\SignupController@ProvisioningCheck'));

    Route::post('/MailGun', array('uses' => 'API\MailgunController@Update'));

    Route::get('{wildcard}', function () {
        return Response::make(view('errors.404'), 404);
    });
});

$router->group($promotions, function ($router) {
    Route::get('', array('uses' => 'Promotions\PromotionController@showPromotions'));
    Route::get('{wildcard}', array('uses' => 'Promotions\PromotionController@showPromotions'));
    Route::get('/Checkout/{wildcard}', array('uses' => 'Promotions\PromotionController@showCheckoutPromotions'));
    Route::post('/', array('uses' => 'Promotions\PromotionController@doSubscription'));
});

$router->group($google, function ($router)
{
    Route::get('/google/', array('uses' => 'OS\GoogleController@Redirect'));
    Route::get('{wildcard}', function () {
        return Response::make(view('errors.404'), 404);
    });
});

$router->group(['domain' => '{account}.officesweeet.com'], function ($router)
{
    #load all route files in Directory /Http/Routes/NotLoggedIn
    foreach ( File::allFiles(app_path() . '/Http/Routes/NotLoggedIn') as $partial )
    {
        require $partial->getPathname();
    }

    //Route::post('MailGun', array('uses' => 'API\MailgunController@Update'));

    $router->group(['middleware' => 'RedirectIfAuthenticated'], function ($router)
    {
        #load all route files in Directory /Http/Routes/System
        foreach ( File::allFiles(app_path() . '/Http/Routes/BasicRoutes') as $partial )
        {
            require $partial->getPathname();
        }


    });

    #stuff all users should have access to
    $router->group(['middleware' => ['LoggedIn']], function ($router)
    {

        require app_path() . '/Http/Routes/Subscription.php';



        Route::get('Setting/{name}', array('uses' => 'ACPController@GetSetting'));

        $router->group(['middleware' => 'CheckSubscription'], function ($router)
        {

            Route::get('', array('uses' => 'OS\Home\HomeController@Redirect'));
            Route::get('VideoPopup', array('uses' => 'OS\Users\LoginController@ShowVideo'));

            //$router->group(['middleware' => 'CheckPermission:os_support_permission'], function ($router) {
            //    Route::get('connectioninfo', array('uses' => 'HomeController@connecttioninfo'));
            //});

            #load all route files in Directory /Http/Routes/System
            foreach ( File::allFiles(app_path() . '/Http/Routes/System') as $partial )
            {
                require $partial->getPathname();
            }


            $router->group(['middleware' => 'CheckBroker'], function ($router) {
                foreach (File::allFiles(app_path() . '/Http/Routes/Broker') as $partial) {
                    require $partial->getPathname();
                }
            });
        });
    });
});
