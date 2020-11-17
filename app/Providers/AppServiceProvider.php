<?php

namespace App\Providers;

use App\Models\OS\Scheduler;
use App\Observers\OS\SchedulerObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

use Http\Client\Curl\Client;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\StreamFactory\DiactorosStreamFactory;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            //var_dump($query);
        });
        
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Configure logging to include files and line numbers
        $monolog       = \Log::getMonolog();
        $introspection = new \Monolog\Processor\IntrospectionProcessor (
            \Monolog\Logger::WARNING, // whatever level you want this processor to handle
            [
                'Monolog\\',
                'Illuminate\\',
            ]
        );
        $monolog->pushProcessor($introspection);

        $this->app->bind('mailgun.client', function() {
            $client = new Client(new DiactorosMessageFactory(), new DiactorosStreamFactory());
            return $client;
            //return \Http\Adapter\Guzzle6\Client::createWithConfig([
                // your Guzzle6 configuration
            //]);
        });
    }
}
