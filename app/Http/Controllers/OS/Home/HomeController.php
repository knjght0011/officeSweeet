<?php

namespace App\Http\Controllers\OS\Home;

use App\Helpers\OS\Users\UserHelper;
use App\Models\Check;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


//Models
use App\Models\Address;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\OS\Clients\Patient;
use App\Models\Clock;

//mail
use App\Mail\LiveDemoValidation;

//helper
use App\Helpers\OS\Financial\PayrollHelper;

class HomeController extends Controller {

    public function Redirect(){
        return Redirect::to('/Home');
    }

    public function showHome()
    {
        if (Auth::user()->hasPermission("client_permission")) {
            $clients = Client::with('primarycontact')->withTrashed()->get();
            $prospects = $clients;
        } else {
            $prospects = null;
            $clients = null;
        }

        if (Auth::user()->hasPermission("patient_permission") ){
            $patients = Patient::with('client')->get();
        }else{
            $patients = null;
        }

        if (Auth::user()->hasPermission("vendor_permission") ){
            $vendors = Vendor::with('primarycontact')->withTrashed()->with('address')->get();
        }else{
            $vendors = null;
        }
        
        if (Auth::user()->hasPermission("employee_permission") ){
            $account = app()->make('account');
            if($account->subdomain === "livedemo"){
                $employees = UserHelper::GetAllUsersCanLogin();
            }else{
                $employees = UserHelper::GetAllUsers();
            }
        }else{
            $employees = null;
        }
        
        return View::make('OS.Home.main')
            ->with('clients', $clients)
            ->with('patients', $patients)
            ->with('prospects', $prospects)
            ->with('vendors', $vendors)
            ->with('employees', $employees);
    }



    public function showHomeClients()
    {
        if (Auth::user()->hasPermission("client_permission") ){
            $clients = Client::with('primarycontact')->withTrashed()->get();
        }else{
            $clients = null;
        }

        $prospects = null;
        $vendors = null;
        $employees = null;
        $patients = null;

        return View::make('OS.Home.main')
            ->with('clients', $clients)
            ->with('patients', $patients)
            ->with('prospects', $prospects)
            ->with('vendors', $vendors)
            ->with('employees', $employees);
    }


    public function showHomePatients()
    {
        if (Auth::user()->hasPermission("patient_permission") ){
            $patients = Patient::with('client')->get();
        }else{
            $patients = null;
        }

        $clients = null;
        $prospects = null;
        $vendors = null;
        $employees = null;

        return View::make('OS.Home.main')
            ->with('clients', $clients)
            ->with('patients', $patients)
            ->with('prospects', $prospects)
            ->with('vendors', $vendors)
            ->with('employees', $employees);
    }

    public function showHomeProspects()
    {
        if (Auth::user()->hasPermission("client_permission") ){
            $prospects = Client::with('primarycontact')->withTrashed()->get();
        }else{
            $prospects = null;
        }

        $clients = null;
        $vendors = null;
        $employees = null;
        $patients = null;

        return View::make('OS.Home.main')
            ->with('clients', $clients)
            ->with('patients', $patients)
            ->with('prospects', $prospects)
            ->with('vendors', $vendors)
            ->with('employees', $employees);
    }

    public function showHomeVendors()
    {
        $clients = null;
        $prospects = null;
        $patients = null;

        if (Auth::user()->hasPermission("vendor_permission") ){
            $vendors = Vendor::with('primarycontact')->withTrashed()->with('address')->get();
        }else{
            $vendors = null;
        }

        $employees = null;

        return View::make('OS.Home.main')
            ->with('clients', $clients)
            ->with('patients', $patients)
            ->with('prospects', $prospects)
            ->with('vendors', $vendors)
            ->with('employees', $employees);
    }

    public function showHomeEmployees()
    {

        $clients = null;
        $prospects = null;
        $vendors = null;
        $patients = null;

        if (Auth::user()->hasPermission("employee_permission") ){
            $account = app()->make('account');
            if($account->subdomain === "livedemo"){
                $employees = UserHelper::GetAllUsersCanLogin();
            }else{
                $employees = UserHelper::GetAllUsers();
            }
        }else{
            $employees = null;
        }

        return View::make('OS.Home.main')
            ->with('clients', $clients)
            ->with('patients', $patients)
            ->with('prospects', $prospects)
            ->with('vendors', $vendors)
            ->with('employees', $employees);
    }

    public function ColSave(){

        $data = array(
            'type' => Input::get('type'),
            'col' => Input::get('col'),
            'status' => Input::get('status'),
            );

        $options = Auth::user()->options;
        $options['HomeCols'][$data['type']][$data['col']] = $data['status'];
        Auth::user()->options = $options;
        Auth::user()->save();

        return ['status' => 'OK'];

    }
}