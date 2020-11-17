<?php
namespace App\Helpers\OS\Users;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Hash;

use App\Models\User;
use App\Models\OS\PasswordReset;

use \App\Providers\EventLog;

use App\Mail\PasswordResetMail;

class UserHelper
{
    const passwordresertimeoutmins = 30;

    public static function ValidatePassword($data){
        // validate the info, create rules for the inputs
        $rules = array(
            #'id' => 'required|exists:users,id',
            'password' => 'required|min:8|same:confirmpassword' // password can only be alphanumeric and has to be greater than 3 characters
        );
        
        if(isset($data['id'])){
            $data['id'] = 'required|exists:users,id';
        }
        
        if(isset($data['token'])){
            $data['token'] = 'required|exists:passwordreset,token';
        }
        

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors
   
    }

    public static function ChangePassword(User $user, string $password){

        $user->password = Hash::make($password);
        $user->passwordlastchanged  = Carbon::now();
        $user->force_password_change = 0;
        $user->save();

    }
    
    public static function RequestResetPassword(User $user, $subdomain)
    {
        $reset = new PasswordReset;
        $reset->user_id = $user->id;
        $reset->token = "";
        $reset->save();
        
        Mail::to($user->email)->send(new PasswordResetMail($user, $reset, $subdomain));

    }    

    public static function ResetPassword(array $data)
    {
        $reset = PasswordReset::where('token',$data['token'])->with('user')->first();
        
        $data['id'] = $reset->user->id;
        
        $validator = self::ValidatePassword($data);

        if ($validator->fails()) {
            return Redirect::to('reset/'.$data['token'])
                    ->withErrors($validator); // send back all errors to the login form
            
        } else {
            if($reset->created_at->diffInMinutes(Carbon::now()) < self::passwordresertimeoutmins){

                self::ChangePassword($reset->user, $data['password']);

                $reset->token = null;
                $reset->save();
                return View::make('Login.message')
                    ->with('message', "passwordchanged" );
            }else{
                return View::make('Login.message')
                    ->with('message', "toold" );
            }
        }
    }

    public static function GetAllUsers()
    {
        if(!app()->bound('users')){

            switch (Auth::user()->getPermission('employee_permission')) {
                case 0:
                    //no permission
                    app()->instance('users', []);
                    break;
                case 1:
                    //Full permission
                    app()->instance('users', User::where('os_support_permission', 0)
                                                        ->withTrashed()
                                                        ->get());

                    break;
                case 2:
                    //Department Permission
                    app()->instance('users', User::where('os_support_permission', 0)
                                                                ->where('department', Auth::user()->department)
                                                                ->withTrashed()
                                                                ->get());

                    break;
            }

            return app()->make('users');
        }else{
            return app()->make('users');
        }
    }

    public static function GetAllUsersWhere($field, $value){

        if(count(self::GetAllUsers()) > 0){
            return self::GetAllUsers()->where($field, $value);
        }else{
            return [];
        }
    }

    public static function GetAllUsersCanLogin(){
        return self::GetAllUsersWhere('canlogin', 1);
    }

    public static function GetOneUserByID($id){
        $users = self::GetAllUsersWhere('id', $id);
        if(count($users) > 0){
            return $users->first();
        }else{
            return [];
        }
    }

    public static function GetOneUserByEmail($email){

        $users = self::GetAllUsersWhere('email', $email);
        if(count($users) > 0){
            return $users->first();
        }else{
            return [];
        }

    }



        //UserHelper::GetAllUsers();
        //UserHelper::GetOneUserByID($id);
        //UserHelper::GetOneUserByEmail($email);
        //UserHelper::GetAllUsersCanLogin();

}
