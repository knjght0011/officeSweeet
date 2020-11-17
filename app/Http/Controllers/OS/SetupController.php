<?php

namespace App\Http\Controllers\OS;


use App\Models\CustomTables;
use App\Models\Template;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Google;

use App\Helpers\OS\SettingHelper;
use App\Helpers\BranchHelper;

class SetupController extends Controller {

    public static function ShowSetup(){
        return View::make('OS.Setup.setup');
    }

    public function SaveData(){

        $data = Input::all();
        $data['id'] = "0"; //for BranchHelper::SaveBranch($data);
        $data['status'] = "Main"; //for BranchHelper::SaveBranch($data);

        $validator = $this->ValidateData($data);

        if ($validator->fails()) {
            return $validator->errors()->toArray(); // send back all errors
        } else {

            #step1
            Auth::user()->password = Hash::make($data['password']);
            Auth::user()->passwordlastchanged  = Carbon::now();
            Auth::user()->save();

            #step2
            SettingHelper::SetSetting('companyname', $data['companyname']);

            $info = app()->make('account')->installinfo;
            if($data['companytype'] === "Other"){
                SettingHelper::SetSetting('companytype', $data['companytype-other']);
                $info['companytype-setup'] = $data['companytype-other'];
            }else{
                SettingHelper::SetSetting('companytype', $data['companytype']);
                $info['companytype-setup'] = $data['companytype'];
                #Future do some data seeding here
                switch ($data['companytype']) {
                    case "Mover":
                        $this->CreateMoverTab();
                        $this->CreateMoverTemplate();
                    break;
                    case "Plumber":

                    break;
                    case "Builder":

                    break;

                    case "Other":
                        break;
                }

            }
            app()->make('account')->installinfo = $info;

            SettingHelper::SaveNewTax($data['sales-tax']);

            #step3
            BranchHelper::SaveBranch($data);

            app()->make('account')->installstage = 7;
            app()->make('account')->save();

            return "success";
        }
    }

    public function ValidateData($data){
        // validate the info, create rules for the inputs
        $rules = array(
            'password' => 'required|string|min:8|same:confirmpassword', // password can only be alphanumeric and has to be greater than 3 characters

            'companyname' => 'required|string',
            'sales-tax' => 'numeric',

            'number'    => 'string',
            'address1'    => 'required|string',
            'address2'    => 'string',
            'city'    => 'string',
            'region'    => 'string',
            'state'    => 'string',
            'zip'    => 'required|string',
            'phonenumber' => 'string',
            'faxnumber' => 'string',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }

    public function CreateMoverTab(){

        $col1 = array(
            "Move_Date_and_Time"=> "input",
            "Load_Address=>"=> "input",
            "Destination_Addr"=> "input",
            "Stairs"=> ["None", "One floor of stairs at present location", "Two floors of stairs at present location", "Three floors of stairs at present location", "One floor of stairs at new location", "Two floors of stairs at new location", "Three floors of stairs at new location"],
            "Bedrooms"=> ["1 Bedroom", "2 Bedrooms", "3 Bedrooms", "4 Bedrooms", "5 Bedrooms", "6 Bedrooms"],
            "Dis-Reassembly"=> "input",
            "Number_of_Boxes"=> "input",
        );

        $col2 = array(
            "Appliances"=> "input",
            "Specialty_Items"=> "textarea,3",
            "Men_Required"=> ["1", "2", "3", "4", "5", "6"],
            "Trips_Expected"=> "input",
            "Est_Hours_for_Job"=> "input",
            "Minimum_Quoted_"=> "input"
        );

        $col3 = array(
            "Packing_Req"=> "input", 
            "Addl_Comments"=> "textarea,3",
            "Trucks_Required"=> ["1", "2", "3", "4", "5", "6"],
            "Rate_per_Hour"=> "input", 
            "Estimate"=> "input"
        );

        $fields = array(
            'col1' => $col1,
            'col2' => $col2,
            'col3' => $col3,
        );

        $tab = new CustomTables;
        $tab->name = "movingdetails";
        $tab->displayname = "Moving Details";
        $tab->type = "client";
        $tab->fields = $fields;
        $tab->save();
    }

    public function CreateMoverTemplate(){

        $content =  "@extends('OS.Templates.templatemaster') @section('template') /n".
                    "<p style=\"text-align:center\"><span style=\"font-size:26px\"><span style=\"font-family:lucida sans unicode,lucida grande,sans-serif\"><strong>Big Muscle Movers</strong></span></span></p>".
                    "<p style=\"text-align:center\"><span style=\"font-family:britannic bold\">4852 MainStreet &ndash; Leesburg, FL &ndash; 32658</span></p>".
                    "<p style=\"text-align:center\"><span style=\"font-family:britannic bold\">352-458-7894 &ndash; BMM@gmail.com</span></p>".
                    "<p>&nbsp;</p>".
                    "<p style=\"text-align:center\"><span style=\"font-size:16px\"><strong>Estimate/Agreement</strong></span></p>".
                    "<p>&nbsp;</p>".
                    "<p><span style=\"font-size:14px\"><strong>Move Date and Time:&nbsp;</strong>{{Link::ClientTabData('movingdetails','Move_Date_and_Time')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Load Address (Start):&nbsp;</strong>{{Link::ClientTabData('movingdetails','Load_Address:')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Unload Address (End): </strong>{{Link::ClientTabData('movingdetails','Destination_Addr')}}&nbsp;</span></p>".
                    "<p>&nbsp;</p>".
                    "<p style=\"text-align:center\"><span style=\"font-size:16px\"><strong>Client Information</strong></span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Name:</strong>&nbsp;{{Link::Client('name')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Client Address:&nbsp;</strong>{{Link::ClientAddress('number')}}&nbsp;{{Link::ClientAddress('address1')}},&nbsp;{{Link::ClientAddress('city')}},&nbsp;{{Link::ClientAddress('state')}}&nbsp;{{Link::ClientAddress('zip')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Phone Number:&nbsp;</strong>{{Link::ClientPrimaryContact('officenumber')}}&nbsp;{{Link::ClientPrimaryContact('mobilenumber')}}&nbsp;{{Link::ClientPrimaryContact('homenumber')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Email Address: </strong>{{Link::ClientPrimaryContact('email')}}&nbsp;</span></p>".
                    "<p>&nbsp;</p>".
                    "<p style=\"text-align:center\"><span style=\"font-size:16px\"><strong>Moving Details</strong></span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Number of Bedrooms: </strong>{{Link::ClientTabData('movingdetails','Bedrooms')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Number of Boxes:&nbsp;</strong>{{Link::ClientTabData('movingdetails','Number_of_Boxes')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Requires Disassembly/Reassembly:&nbsp;</strong>{{Link::ClientTabData('movingdetails','Dis-Reassembly')}}<strong>&nbsp;</strong></span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Stairs:</strong>&nbsp;{{Link::ClientTabData('movingdetails','Stairs')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Appliances:&nbsp;</strong>{{Link::ClientTabData('movingdetails','Appliances')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Packing Requirements:&nbsp;</strong>{{Link::ClientTabData('movingdetails','Packing_Req')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Specialty Items:&nbsp;</strong>{{Link::ClientTabData('movingdetails','Specialty_Items')}}<strong>&nbsp;</strong></span></p>".
                    "<p><span style=\"font-size:14px\"><strong>Additional Comments:&nbsp;</strong>{{Link::ClientTabData('movingdetails','Addl_Comments')}}&nbsp;</span></p>".
                    "<p>&nbsp;</p>".
                    "<p style=\"text-align:center\"><u><span style=\"font-size:16px\"><strong>Estimate</strong></span></u></p>".
                    "<p><span style=\"font-size:14px\">Based on the information obtained to date, we estimate this job will require {{Link::ClientTabData('movingdetails','Men_Required')}}&nbsp;men, using {{Link::ClientTabData('movingdetails','Trucks_Required')}}&nbsp;truck(s) for {{Link::ClientTabData('movingdetails','Est_Hours_for_Job')}}&nbsp;hours at {{Link::ClientTabData('movingdetails','Rate_per_Hour')}}&nbsp;per hour. This is expected to be completed in {{Link::ClientTabData('movingdetails','Trips_Expected')}}&nbsp;trip(s) total.</span></p>".
                    "<p><strong><span style=\"font-size:14px\">Therefore, based on the above the complete estimate comes to {{Link::ClientTabData('movingdetails','Estimate')}}&nbsp;.</span></strong></p>".
                    "<p><span style=\"font-size:14px\">The undersigned customer understands that this is an estimate whereby the cost may be higher or lower depending on circumstances beyond the control of {{Link::CompanyInfo('name')}}. We promise to everything in our power to work efficiently and safely.&nbsp; Nevertheless, the minimum rate remains at {{Link::ClientTabData('movingdetails','Minimum_Quoted_')}}. Any and all deposits will be duly credited to the cost of this move.</span></p>".
                    "<p><span style=\"font-size:14px\">The undersigned agrees to hire {{Link::CompanyInfo('name')}}&nbsp;based on the above information.</span></p>".
                    "<p>&nbsp;</p>".
                    "<p><span style=\"font-size:14px\">___________________________ {{Link::General('currentdatewords')}}&nbsp;</span></p>".
                    "<p><span style=\"font-size:14px\">{{Link::Client('name')}}&nbsp;</span></p>".
                    "/n@stop";

        $template = new Template;
        $template->name = "Moving Estimate";
        $template->content = $content;
        $template->type = "client";
        $template->user_id = Auth::user()->id;
        $template->subgroup = "Moving";
        $template->save();
    }
}
