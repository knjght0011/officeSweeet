<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventLog extends ServiceProvider
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
    
    public static function add($event)
    {
        $event_data = new Event;
        $event_data->user_id = Auth::user()->id;
        $event_data->event = $event;
        $event_data->save();            
    }

}
