<?php

namespace App\Http\Controllers\OS\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Session;

use App\Models\User;

class UserAPIController extends Controller
{
    private function Auth($input){

        $userdata = array(
            'email'     => trim ($input['email']),
            'password'  => $input['password'],
            'canlogin'  => 1
        );

        return Auth::attempt($userdata);
    }

    public function CanLogin(){

        if($this->Auth(Input::all())){
            $user = User::where('os_support_permission' , 0)->where('canlogin', 1)->get();
            return $user;
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

}
