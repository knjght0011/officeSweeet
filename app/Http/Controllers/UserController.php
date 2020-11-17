<?php
namespace App\Http\Controllers;

#use App\Http\Controllers\Controller;
use App\Helpers\OS\Users\UserHelper;
use Illuminate\Support\Facades\Auth;


#use App\Models\Management\Account;
use Hash;
#use Illuminate\Support\Facades\Redirect;
#use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Models\User;

use App\Mail\UserInvite;

class UserController extends Controller {
    
    public function NumberOfSlots()#hardcoded for now, in future will check how many they are licenced for.
    {
        $account = app()->make('account');
        if(count($account) ===  1){
            if($account->licensedusers === 0){
                return 999999999999;
            }else{
                return $account->licensedusers;
            }
        }else{
            return 0;
        }
    }
    
    #Route::post('Users/ChangePassword', array('uses' => 'UserController@ChangePassword'));
    public function SavePassword()
    {

        $data = array(
            'id' => Input::get('id'),
            'password' => Input::get('password'), 
            'confirmpassword' => Input::get('confirmpassword'), 
        );
        
        //$validator = $this->ValidatePassword($data);
        $validator = UserHelper::ValidatePassword($data);

        if ($validator->fails()) {
            return ["status" => "validation", "errors" => $validator->errors()->toArray()] ; // send back all errors
        } else {
            $user = UserHelper::GetOneUserByID($data['id']);
            if(count($user) === 1){
                UserHelper::ChangePassword($user, $data['password']);

                return ["status" => "OK"];
            }else{
                return ["status" => "unabletofinduser"];
            }
        }
    }

    public function EnableLogin(){
        $data = Input::all();

        $slots = $this->NumberOfSlots();

        $count = count(UserHelper::GetAllUsersCanLogin());

        if($count >= $slots){
            return ['status' => 'noslots', 'slots' => $slots, 'count' => $count];
        }else{
            $user = UserHelper::GetOneUserByID($data['id']);
            if(count($user) === 1){

                $invite = $data['send-invite-toggle'];
                $password = str_random(10);

                unset($data['id']);
                unset($data['send-invite-toggle']);
                unset($data['_token']);

                $permissions = array();
                foreach($data as $key => $value){
                    $permissions[$key] = $value;
                }

                $user->permissions = $permissions;
                $user->password = Hash::make($password);
                $user->canlogin = 1;
                $user->force_password_change = 1;
                $user->save();

                if($invite === "1"){
                    Mail::to($user->email)->send(new UserInvite($user, $password, Auth::user()->firstname . " " . Auth::user()->lastname, app()->make('account')->subdomain));
                }

                return ['status' => 'OK'];
            }else{
                return ['status' => 'unabletofinduser'];
            }

        }

    }

    public function DisableLogin(){
        $data = Input::all();

        $user = UserHelper::GetOneUserByID($data['id']);
        if(count($user) === 1){

            $user->canlogin = 0;
            $user->save();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'unabletofinduser'];
        }
    }

    public function EnablePermission()
    {
        $data = array(
            'id' => Input::get('id'),
            'permission' => Input::get('permission'),
        );

        $user = UserHelper::GetOneUserByID($data['id']);

        if(count($user) === 1){

            if(Schema::hasColumn('users', $data['permission'])){

                $array = array($data['permission'] => 1);
                $user->update($array);
                $user->save();
            }

            $permissions = $user->permissions;
            $permissions[$data['permission']] = 1;
            $user->permissions = $permissions;
            $user->save();

            return ['status' => 'OK'];
        }
    }

    public function DisablePermission()
    {
        $data = array(
            'id' => Input::get('id'),
            'permission' => Input::get('permission'),
            'action' => Input::get('action'),
        );


        $user = UserHelper::GetOneUserByID($data['id']);

        if(count($user) === 1){

            if(Schema::hasColumn('users', $data['permission'])){

                $array = array($data['permission'] => 0);
                $user->update($array);
                $user->save();
            }

            $permissions = $user->permissions;
            $permissions[$data['permission']] = 0;
            $user->permissions = $permissions;
            $user->save();

            return ['status' => 'OK'];

        }
    }

    public function SetPermission()
    {
        $data = array(
            'id' => Input::get('id'),
            'permission' => Input::get('permission'),
            'level' => Input::get('level'),
        );

        $user = UserHelper::GetOneUserByID($data['id']);

        if(count($user) === 1){

            if($user->id === Auth::user()->id and $data['permission'] === "employee_permission"){
                return ['status' => 'notallowed'];
            }else{
                if(Schema::hasColumn('users', $data['permission'])){

                    $array = array($data['permission'] => $data['level']);
                    $user->update($array);
                    $user->save();
                }

                $permissions = $user->permissions;
                $permissions[$data['permission']] = intval($data['level']);
                $user->permissions = $permissions;
                $user->save();

                return ['status' => 'OK'];
            }
        }else{
            return ['status' => 'notfound'];
        }
    }


    public function ValidatePermission($data){
        // validate the info, create rules for the inputs
        $rules = array(
            'id' => 'required|exists:users,id',
            #'permission' => 'in:acp_permission,client_permission,vendor_permission,employee_permission,reporting_permission,journal_permission,deposits_permission,checks_permission,reciepts_permission,payroll_permission',
        );

        $permission = 'in:';
        foreach(Auth::user()->PermissionsArray() as $key => $value){
            $permission = $permission . $key . ",";
        }
        $rules['permission'] = $permission;


        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }


    
    public function ValidateLoginStatus($data){
        // validate the info, create rules for the inputs
        $rules = array(
            'id' => 'required|exists:users,id',
            'action' => 'in:enable,disable',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }

    /*
    #################
    #################
    #defunct Users now created in employee controller
    #
    #
    #
    #
    #
    #
    #
    #
    #
    #
    #
    #
    #


    public function ToggleLoginStatus($subdomain){

        $data = array(
            'id' => Input::get('id'),
            'action' => Input::get('action'),
        );

        $validator = $this->ValidateLoginStatus($data);

        if ($validator->fails()) {
            return $validator->errors()->toArray(); // send back all errors
        } else {

            $user = User::find($data['id']);

            if($data['action'] == "enable"){

                $slots = $this->NumberOfSlots();

                $count = User::where('canlogin', 1)->count();

                if($count >= $slots){
                    return "#,".$slots.",".$count;
                }else{
                    $user->canlogin = 1;
                    $user->save();

                    return "enabled";
                }
            }else{
                $user->canlogin = 0;
                $user->save();

                return "disabled";
            }
        }
    }
    #Route::post('Users/Save/NewUser', array('uses' => 'UserController@NewUser'));
    public function NewUser(){
        
        $data = array(
            'email' => Input::get('email'), 
            'password' => Input::get('password'), 
            'confirmpassword' => Input::get('confirmpassword'), 
        );
        
        $validator = $this->ValidateUser($data);
        
        if ($validator->fails()) {
            return $validator->errors()->toArray(); // send back all errors
        } else {
            $user_data = new User;
            $user_data->email = Input::get('email');
            $user_data->password = Hash::make(Input::get('password'));
            $user_data->passwordlastchanged  = Carbon::now();
            $user_data->force_password_change = 0;
            $user_data->save();
            
            return "success";
        }    
        
    }
    
    public function ValidateUser($data){
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email|unique:users,email', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3|same:confirmpassword' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors
   
    }
    
    #Route::get('Users/All', array('uses' => 'UserController@Data'));
    public function Data()
    {

        $users = User::all();
        return $users;

    }

    #Route::post('Users/ChangeEmail', array('uses' => 'UserController@ChangeEmail'));
    public function SaveEmail()
    {   
        $data = array(
            'id' => Input::get('id'),
            'email' => Input::get('email'), // make sure the email is an actual email
        );
        
        $validator = $this->ValidateEmail($data);

        if ($validator->fails()) {
            return $validator->errors()->toArray(); // send back all errors
        } else {
            $user = User::find($data['id']);
            $user->email = $data['email'];
            $user->save();

            return "success";
        }
    }
    
    public function ValidateEmail($data){
        // validate the info, create rules for the inputs
        $rules = array(
            'id' => 'required|exists:users,id',
            'email' => 'required|email|unique:users,email', // make sure the email is an actual email
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    
    
    #Route::post('Users/Save/Branch', array('uses' => 'UserController@SaveBranch'));
    public function SaveBranch()
    { 
        $data = array(
            'id' => Input::get('id'),
            'branch_id' => Input::get('branch'), // make sure the email is an actual email
        );
        
        $validator = $this->ValidateBranch($data);

        if ($validator->fails()) {
            return $validator->errors()->toArray(); // send back all errors
        } else {
            $user = User::find($data['id']);
            $user->branch_id = $data['branch_id'];
            $user->save();

            return "success";
        }
    }
    
    public function ValidateBranch($data){
        // validate the info, create rules for the inputs
        $rules = array(
            'id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id', // make sure the email is an actual email
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
        #Route::post('Users/Save/Permissions', array('uses' => 'UserController@SavePermissions'));
    public function SavePermissions()
    {
        $data = array(
            'id' => Input::get('id'),
            'locked' => Input::get('locked'), 
            'disabled' => Input::get('disabled'), 
            'usermanagement' => Input::get('usermanagement'), 
        );
        
        $validator = $this->ValidatePermissions($data);

        if ($validator->fails()) {
            return $validator->errors()->toArray(); // send back all errors
        } else {
            $user = User::find($data['id']);
            $user->locked = $data['locked'];
            $user->disabled = $data['disabled'];
            $user->usermanagement = $data['usermanagement'];            
            $user->save();

            return "success";
        }        
    } 
    
    public function ValidatePermissions($data){
        // validate the info, create rules for the inputs
        $rules = array(
            'id' => 'required|exists:users,id',
            'locked' => '',
            'disabled' => '',
            'usermanagement' => '',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    */
}