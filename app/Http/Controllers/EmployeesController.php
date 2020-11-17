<?php

namespace App\Http\Controllers;

use App\Helpers\OS\Address\AddressHelper;
use App\Helpers\OS\Financial\PayrollHelper;
use App\Helpers\OS\Scheduler\ScheduleHelper;
use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;

use App\Models\OS\Scheduler;
use App\Models\OS\SchedulerParent;
use App\Models\OS\Training\TrainingModule;
use App\Models\OS\Training\TrainingRequest;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

use \App\Providers\EventLog;

use App\Models\Branch;
use App\Models\User;
use App\Models\EmployeeNote;

use App\Helpers\EmployeeHelper;


class EmployeesController extends Controller {



    #Generate Pages
    public function showMain()
    {
        $account = app()->make('account');
        if($account->subdomain === "livedemo"){
            $employees = UserHelper::GetAllUsersCanLogin();
        }else{
            $employees = UserHelper::GetAllUsers();
        }

        $freqencysetting = Setting::where('name' , 'Payroll-Frequency')->first();
        $optionsetting = Setting::where('name' , 'Payroll-Option')->first();
        $firstsetting = Setting::where('name' , 'Payroll-First')->first();

        if($freqencysetting != null){
            if($firstsetting != null){
                $payroll =  PayrollHelper::GetNextPayPeriod($freqencysetting->value, $optionsetting->value, $firstsetting->value);
            }else{
                $payroll = null;
            }
        }else{
            $payroll = null;
        }

        return View::make('Employees.main')
            ->with('payroll', $payroll)
            ->with('employees', $employees);

    }
    
    public function showAdd()
    {
        $branches = Branch::all();
        return View::make('Employees.edit')
            ->with('branches', $branches);
    }
    
    
    public function showView($subdomain, $id, $page = null){
        $selected_employee = intval($id);
        if ($selected_employee !== 0){

            $employee = User::where('id', $selected_employee)
                        ->withTrashed()
                        ->with('clocks')
                        ->with('address')
                        ->with('receipts')
                        ->with('signing')
                        ->with('checks')
                        ->with('branch')
                        ->with('billablehours')
                        ->with('billablehours.client')
                        ->with('notes1')
                        ->with('TrainingRequests')
                        ->with('TrainingRequests.TrainingModule')
                        ->with('TrainingRequests.Schedule')
                        ->with('TrainingRequests.Schedule.scheduler')
                        ->first();

            $TrainingModules = TrainingModule::withTrashed()->get();
            
            return View::make('Employees.view')
                    ->with('TrainingModules', $TrainingModules)
                    ->with('page' , $page)
                    ->with('employee',$employee);
            
        } else {
            return Redirect::to('/Employees/Search');
        }
    }
    
    public function showEdit($subdomain, $id){
        $selected_employee = intval($id);
        if ($selected_employee !== 0){
            
            
            $employee = UserHelper::GetOneUserByID($selected_employee);
            
            $branches = Branch::all();
            
            return View::make('Employees.edit')
                    ->with('employee',$employee)
                    ->with('branches',$branches);
            
        } else {
            return Redirect::to('/Employees/Search');
        }
    }
    
    public function ViewPayroll()
    {        
        return View::make('Employees.payroll');
    }
    
    public function SaveEmployee(){

        $data = array(
            'id' => intval(Input::get('id')),
            'firstname' => Input::get('firstname'),
            'middlename' => Input::get('middlename'),
            'lastname' => Input::get('lastname'),
            'ssn' => Input::get('ssn'),
            'driverslicense' => Input::get('driverslicense'),
            'email' => Input::get('email'),
            'phonenumber' => Input::get('phonenumber'),
            'department' => Input::get('department'),
            'employeeid' => Input::get('employeeid'),
            #'address_id' => Input::get('address_id'),
            'branch_id' => Input::get('branch_id'),
            'type' => Input::get('type'),
            'start-date' => Input::get('start-date'),
            'end-date' => Input::get('end-date'),

            'emergency_contact_name' => Input::get('emergency_contact_name'),
            'emergency_contact_relationship' => Input::get('emergency_contact_relationship'),
            'emergency_contact_phone_number' => Input::get('emergency_contact_phone_number'),

        );

        if($data['branch_id'] === "none"){
            $data['branch_id'] = null;
        }

        $addressdata = array(
            'number' => Input::get('number'),
            'address1' => Input::get('address1'),
            'address2' => Input::get('address2'),
            'city' => Input::get('city'),
            'region' => Input::get('region'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
            'type' => "",
        );

        $addressvalidator = AddressHelper::ValidateAddressInput($addressdata);
        $validator = EmployeeHelper::ValidateEmployeeInput($data);

        if ($validator->fails() or $addressvalidator->fails()){
            return ['status' => 'validation', 'errors' => array_merge ( $validator->errors()->toArray(), $addressvalidator->errors()->toArray())];
        } else {

            $address = AddressHelper::SaveAddress($addressdata);
            $data['address_id'] = $address->id;
            
            $employee = EmployeeHelper::UpdateEmployeeInDB($data);
            
            return ['status' => 'OK', 'id' => $employee->id];
        }
    }
    
    public function GetEmployeeeInput()
    {
        
        $data = array(
            'id' => intval(Input::get('id')),
            'firstname' => Input::get('firstname'),
            'middlename' => Input::get('middlename'),
            'lastname' => Input::get('lastname'),
            'ssn' => Input::get('ssn'),            
            'driverslicense' => Input::get('driverslicense'),
            'email' => Input::get('email'),
            'phonenumber' => Input::get('phonenumber'),
            'department' => Input::get('department'),            
            'employeeid' => Input::get('employeeid'),
            'address_id' => Input::get('address_id'),
            'branch_id' => Input::get('branch_id'),
            'type' => Input::get('type'),
            'start-date' => Input::get('start-date'),
            'end-date' => Input::get('end-date'),

            'emergency_contact_name' => Input::get('emergency_contact_name'),
            'emergency_contact_relationship' => Input::get('emergency_contact_relationship'),
            'emergency_contact_phone_number' => Input::get('emergency_contact_phone_number'),

        );
        
        if($data['branch_id'] === "none"){
            $data['branch_id'] = null;
        }
           
        return $data;
    }
    
    public function SaveEmployeeCompensation(){

        if(SettingHelper::GetSetting('Payroll-Frequency') === null){
            return ['status' => 'nopayroll'];
        }else{
            $data = array(
                'id' => intval(Input::get('id')),
                'type' => intval(Input::get('type')),
                'ssn' => Input::get('ssn'),
                'rate' => Input::get('rate'),
                'frequency' => Input::get('frequency'),
            );

            $employee = UserHelper::GetOneUserByID($data['id']);
            $employee->type = $data['type'];

            if($data['ssn'] != "LOCKED"){
                $employee->ssn = $data['ssn'];
            }

            if($data['rate'] != "LOCKED"){
                $employee->rate = $data['rate'];
            }

            $employee->frequency = $data['frequency'];
            $employee->save();

            return ['status' => 'OK'];
        }

    }

    public function SaveEmployeeCommission(){

        $data = array(
            'id' => intval(Input::get('id')),
            'product-commission' => Input::get('product-commission'),
            'service-commission' => Input::get('service-commission'),
        );

        $employee = UserHelper::GetOneUserByID($data['id']);

        if(count($employee) === 1){
            $employee->service_commission = $data['service-commission'];
            $employee->product_commission = $data['product-commission'];
            $employee->save();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'notfound'];
        }
    }

    public function Unlock(){

        $userdata = array(
            'email'     => trim (Input::get('email')),
            'password'  => Input::get('password'),
            'canlogin'  => 1
        );

        $employee = User::where('id', Input::get('id'))->first();

        {{ }}

        if(count($employee) === 1){
            if(Auth::attempt($userdata)){
                return ['status' => 'OK', 'ssn' => $employee->ssn ];
            }else{
                return ['status' => 'failed'];
            }
        }else{
            return ['status' => 'notfound'];
        }


    }

    public function AddNote()
    {

        $notedata = array(
            'user_id' => Input::get('user_id'),
            'note' => Input::get('note'),
        );

        $validator = $this->ValidateNoteInput($notedata);


        if ($validator->fails()){

            return $validator->errors()->toArray();

        } else {

            $note_data = new EmployeeNote;
            $note_data->creator_id = Auth::id();
            $note_data->user_id = $notedata['user_id'];
            $note_data->note = $notedata['note'];
            $note_data->save();

            EventLog::add('Note added to client ID:'.$note_data->client_id);

            #return $note_data->id;
            return "success";

        }
    }

    public function ValidateNoteInput($data)
    {
        $rules = array(
            'user_id' => 'exists:users,id',
            'note' => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;
    }

    public function Status(){

        $user = UserHelper::GetOneUserByID(Input::get('id'));

        if(count($user) === 1){
            if(Input::get('action') === "1"){
                $user->restore();
                return ['status' => 'OK', 'action' => 'enabled'];
            }else{
                $user->canlogin = 0;
                $user->save();
                $user->delete();
                return ['status' => 'OK', 'action' => 'disabled'];
            }
        }else{
            return ['status' => 'notfound'];
        }
    }


/*  
 *  
    public function ValidateEmployeeInput($data)
    {

        $rules = array(
            'firstname' => 'required|string',
            'middlename' => '',
            'lastname' => 'required|string',
            'ssn' => '',
            'driverslicense' => '',
            'email' => 'required|email',
            'department' => '',         
            'employeeid' => '',
            'address_id' => 'exists:address,id', 
            'type' => 'in:1,2,3',
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
    
    public function UpdateEmployeeInDB($data)
    {
        if ($data['id'] == 0)
        {
            
            $employee = new User;
            
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

            if ($data['email'] != null) {
                $employee->email = $data['email'];
            }

            if ($data['department'] != null) {
                $employee->department = $data['department'];
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
            EventLog::add('New Employee ID:'.$employee->id.' Name:'.$employee->firstname.' '.$employee->middlename.' '.$employee->lastname);

            return $employee;

        }else{

            $employee = User::find($data['id']);
            
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

            if ($data['email'] != null) {
                $employee->email = $data['email'];
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


            $employee->save();

            //log event
            EventLog::add('Employee edited ID:'.$employee->id.' Name:'.$employee->firstname.' '.$employee->middlename.' '.$employee->lastname);

            return $employee;

        } 
    }
 */
}