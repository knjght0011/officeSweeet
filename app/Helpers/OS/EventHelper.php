<?php
namespace App\Helpers\OS;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventHelper
{
    public static function add($event)
    {
        $event_data = new Event;
        if (Auth::check() == false) {
            $event_data->user_id = null;
        }else{
            $event_data->user_id = Auth::user()->id;
        }
        $event_data->event = $event;
        $event_data->save();            
    }

    public static function Error($error, $debug = "")
    {
        if (is_array($error)){
            return View::make('error')
                ->with('errors', $error)
                ->with('debug', $debug);
        }else{
            return View::make('error')
                ->with('error', $error)
                ->with('debug', $debug);
        }
    }
}