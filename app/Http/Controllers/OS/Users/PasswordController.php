<?php
namespace App\Http\Controllers\OS\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

#models
use App\Models\User;
use App\Models\OS\PasswordReset;

#helpers
use App\Helpers\OS\Users\UserHelper;

class PasswordController extends Controller {
    
    const timeoutmins = 30;
    
    public function showResetRequest(){
        return View::make('Login.resetrequest');
    }
    
    public function RequestReset($account)
    {  
        $email = Input::get('email');
        $user = User::where('email', $email)->first();
        if(count($user) === 1){
            //send reset email
            UserHelper::RequestResetPassword($user, $account);
            return View::make('Login.message')
                    ->with('message', "Passwordresetsent" );
        }else{
            if(count($user) === 0){
                //no user found
                return View::make('Login.message')
                    ->with('message', "Passwordresetsent" );
            }else{
                //mulitiplue users with same email???? shouldnt be able to happen, probably wants to be flagged eventualy
                return View::make('Login.message')
                    ->with('message', "Passwordresetsent" );
            }
        }
    } 

    public function showResetRequestEmail($account){
        $user = User::where('email', 'mcgreeb@muckabout.org')->first();
        $reset = PasswordReset::where('id', '10')->first();
        return View::make('Emails.passwordreset')
                ->with('subdomain', $account )
                ->with('user', $user )
                ->with('reset', $reset );
    }
    
    public function showReset($account,$id){

        $reset = PasswordReset::where('token',$id)->first();

        
        if(count($reset) === 1){
            if($reset->created_at->diffInMinutes(Carbon::now()) < UserHelper::passwordresertimeoutmins){
                return View::make('Login.reset')
                    ->with('token', $id);
            }else{
                return View::make('Login.message')
                    ->with('message', "toold" );
            }
        }else{
            return View::make('Login.message')
                ->with('message', "failedtofind" );
        }
    }
    
    public function Reset($account)
    {  
        $data = array(
            'password' => Input::get('password'),
            'confirmpassword' => Input::get('password-repeat'),
            'token' => Input::get('token'),
        );

        return UserHelper::ResetPassword($data);

    } 
}