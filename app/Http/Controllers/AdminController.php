<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\Setting;
use App\Models\User;

class AdminController extends Controller {
    
        public function showSession()
        {
            return View::make('Admin.session'); 
        }
    
        public function showAdmin()
        {
            if (auth::user()->usermanagement === 1){
                return View::make('Admin.default');
            }else{
                return Redirect::to('/')
                    ->withErrors('Permission Denied');
            }     
        }
       
}
