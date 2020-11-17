<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Models\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];
    
    #protected $defer = false;

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
   public function boot()
    {
        //

        parent::boot();
    }
    
        
    public function ValidateEventInput($data){
        $rules = array(
                    'user_id'    => 'required|exists:users,id', 
                    'event'    => 'required',
                );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            Return $validator; // send back all errors
        } else {
            Return TRUE;
        }
    }
    
    public function log($event)
    {

        $event_data = new Event;
        $event_data->user_id = Auth::user()->id;
        $event_data->address_id = $event;
        $event_data->save();
            
    }
    
}
