<?php
namespace App\Helpers;

#use Carbon\Carbon;
use App\Helpers\OS\Users\UserHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use \App\Providers\EventLog;

class EmployeeHelper
{
    #const writeusername = "managementwrite";
    #const writepassword = "s3T4Yaka8A2ixOw3Ba6E41fOWOlac1";
    #const readusername = "management";
    #const readpassword = "G1YuqoBEmIlIS2bI8AKaY5Go4e2imu";
    public static function ValidateEmployeeInput($data)
    {

        $rules = array(
            'firstname' => 'required|string',
            'middlename' => '',
            'lastname' => 'required|string',
            'ssn' => '',
            'driverslicense' => '',
            'email' => 'required|email|unique:users,email,'.$data['id'],
            'phonenumber' => '',
            'department' => '',         
            'employeeid' => '',
            'address_id' => 'exists:address,id', 
            'type' => 'in:1,2,3,4',
        );
        
        if($data['branch_id'] !== null){
            $rules['branch_id'] = 'exists:branches,id';
        }
        
        if($data['id'] !== 0){
            $rules['id'] = 'exists:users,id';
        }
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    public static function UpdateEmployeeInDB($data, $log = true)
    {
        
        if ($data['id'] == 0)
        {
            
            $employee = new User;
            if (array_key_exists('firstname',$data)){            
                if ($data['firstname'] != null) {
                    $employee->firstname = $data['firstname'];
                }
            }
            
            if (array_key_exists('middlename',$data)){
                if ($data['middlename'] != null) {
                    $employee->middlename = $data['middlename'];
                }
            }

            if (array_key_exists('lastname',$data)){
                if ($data['lastname'] != null) {
                    $employee->lastname = $data['lastname'];
                }
            }
            
            if (array_key_exists('address_id',$data)){
                if ($data['address_id'] != null) {
                    $employee->address_id = $data['address_id'];
                }
            }else{
                $employee->address_id = null;
            }
            
            if (array_key_exists('ssn',$data)){
                if ($data['ssn'] != null) {
                    $employee->ssn = $data['ssn'];
                }
            }
            
            if (array_key_exists('driverslicense',$data)){
                if ($data['driverslicense'] != null) {
                    $employee->driverslicense = $data['driverslicense'];
                }
            }
            
            if (array_key_exists('email',$data)){
                if ($data['email'] != null) {
                    $employee->email = $data['email'];
                }
            }
            
            if (array_key_exists('phonenumber',$data)){
                if ($data['phonenumber'] != null) {
                    $employee->phonenumber = $data['phonenumber'];
                }
            }
            
            if (array_key_exists('password',$data)){
                if ($data['password'] != null) {
                    $employee->password = $data['password'];
                }
            }
            
            if (array_key_exists('department',$data)){            
                if ($data['department'] != null) {
                    $employee->department = $data['department'];
                }
            }            

            #if ($data['branch_id'] != null) {
                $employee->branch_id = $data['branch_id'];
            #}
            if (array_key_exists('type',$data)){            
                if ($data['type'] != null) {
                    $employee->type = $data['type'];
                }
            }
            
            if (array_key_exists('os_support_permission',$data)){            
                if ($data['os_support_permission'] != null) {
                    $employee->os_support_permission = $data['os_support_permission'];
                }
            }

            if (array_key_exists('start-date',$data)){
                if ($data['start-date'] != null) {
                    if($data['start-date'] === ""){
                        $employee->start_date = null;
                    }else{
                        $employee->start_date = $data['start-date'];
                    }
                }
            }

            if (array_key_exists('end-date',$data)){
                if ($data['end-date'] != null) {
                    if($data['end-date'] === ""){
                        $employee->end_date = null;
                    }else{
                        $employee->end_date = $data['end-date'];
                    }
                }
            }

            if (array_key_exists('emergency_contact_name',$data)){
                $employee->emergency_contact_name = $data['emergency_contact_name'];
            }

            if (array_key_exists('emergency_contact_relationship',$data)){
                $employee->emergency_contact_relationship = $data['emergency_contact_relationship'];
            }

            if (array_key_exists('emergency_contact_phone_number',$data)){
                $employee->emergency_contact_phone_number = $data['emergency_contact_phone_number'];
            }

            $employee->save();
            
            $empid = $employee->id;
            
            while (strlen($empid) < 5 ) {
                $empid = "0" . $empid;
            }
            
            $data['employeeid'] = substr($data['firstname'],0,1) . substr($data['lastname'],0,1) . $empid;
            
            if ($data['employeeid'] != null) {
                $employee->employeeid = $data['employeeid'];
            }

            $employee->save();

            //log event
            if($log){
                EventLog::add('New Employee ID:'.$employee->id.' Name:'.$employee->firstname.' '.$employee->middlename.' '.$employee->lastname);
            }

            return $employee;

        }else{

            $employee = UserHelper::GetOneUserByID($data['id']); //User::where('id', '=', $data['id'])->withTrashed()->first();
            
             if ($data['firstname'] != null) {
                $employee->firstname = $data['firstname'];
            }

            if ($data['middlename'] != null) {
                $employee->middlename = $data['middlename'];
            }

            if ($data['lastname'] != null) {
                $employee->lastname = $data['lastname'];
            }

            if ($data['address_id'] != null) {
                $employee->address_id = $data['address_id'];
            }

            if ($data['ssn'] != null) {
                $employee->ssn = $data['ssn'];
            }

            if ($data['driverslicense'] != null) {
                $employee->driverslicense = $data['driverslicense'];
            }

            if ($employee->email != $data['email']) {
                $employee->email = $data['email'];
            }
                        
            if ($data['phonenumber'] != null) {
                $employee->phonenumber = $data['phonenumber'];
            }

            if ($data['department'] != null) {
                $employee->department = $data['department'];
            }

            if ($data['employeeid'] != null) {
                $employee->employeeid = $data['employeeid'];
            }

            if ($data['address_id'] != null) {
                $employee->address_id = $data['address_id'];
            }

            #if ($data['branch_id'] != null) {
                $employee->branch_id = $data['branch_id'];
            #}
            
            if ($data['type'] != null) {
                $employee->type = $data['type'];
            }

            if (array_key_exists('start-date',$data)){
                if ($data['start-date'] != null) {
                    if($data['start-date'] === ""){
                        $employee->start_date = null;
                    }else{
                        $employee->start_date = $data['start-date'];
                    }
                }
            }

            if (array_key_exists('end-date',$data)){
                if ($data['end-date'] != null) {
                    if($data['end-date'] === ""){
                        $employee->end_date = null;
                    }else{
                        $employee->end_date = $data['end-date'];
                    }
                }
            }

            if (array_key_exists('emergency_contact_name',$data)){
                $employee->emergency_contact_name = $data['emergency_contact_name'];
            }

            if (array_key_exists('emergency_contact_relationship',$data)){
                $employee->emergency_contact_relationship = $data['emergency_contact_relationship'];
            }

            if (array_key_exists('emergency_contact_phone_number',$data)){
                $employee->emergency_contact_phone_number = $data['emergency_contact_phone_number'];
            }


            $employee->save();

            //log event
            if($log){
                EventLog::add('Employee edited ID:'.$employee->id.' Name:'.$employee->firstname.' '.$employee->middlename.' '.$employee->lastname);
            }

            return $employee;

        } 
    }

    public static function SwitchConnection($database, $username, $password, $port = "3306")
    {
        config(['database.connections.subdomain.username' => $username]);
        config(['database.connections.subdomain.password' => $password]);
        config(['database.connections.subdomain.port' => $port]);
        config(['database.connections.subdomain.database' => $database]);
        \DB::connection('subdomain')->reconnect();
    }

    /*
    public static function MigratePermissions($account){
        self::SwitchConnection($account->database, $account->username, $account->password, $account->port);

        foreach(User::all() as $user){
            $user->permissions = $user->PermissionsArray();
            $user->save();
        }

        return "done";
    }*/

    public static function AllDepartments(){

        $departments = array();

        foreach(UserHelper::GetAllUsers() as $user){
            if(!in_array($user->department, $departments)){
                $departments[] = $user->department;
            }
        }

        if (($key = array_search("", $departments)) !== false) {
            unset($departments[$key]);
        }

        return $departments;
    }
}
