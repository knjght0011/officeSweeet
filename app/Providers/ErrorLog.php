<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Error;
use Illuminate\Support\Facades\Auth;

class ErrorLog extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
    
    public static function add($error)
    {
        $event_data = new Error;
        $event_data->user_id = Auth::user()->id;
        $event_data->error = $error;
        $event_data->save();            
    }

}
