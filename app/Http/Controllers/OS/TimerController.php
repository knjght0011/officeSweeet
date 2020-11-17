<?php

namespace App\Http\Controllers\OS;

use App\Http\Controllers\Controller;

use App\Models\Client;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function show(){
        return View::make('OS.Timer.timer');
    }

    public function showWithClient($subdomain, $id){

        $client = Client::where('id', '=', $id)->first();

        if(count($client) === 1){
            return View::make('OS.Timer.timer')
                ->with('client', $client);
        }else{
            return "unknown client";
        }
    }
}
