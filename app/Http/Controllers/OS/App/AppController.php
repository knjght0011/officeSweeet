<?php

namespace App\Http\Controllers\OS\App;

use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;




class AppController extends Controller
{
    public function CheckLogin(){

        $data = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            );

        $userdata = array(
            'email'     => trim ($data['email']),
            'password'  => trim ($data['password']),
            'canlogin'  => 1
        );

        if (Auth::attempt($userdata)){
            return ['status' => 'Auth'];
        }else{
            return ['status' => 'failed'];
        }

    }
}
