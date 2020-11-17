<?php
namespace App\Helpers\OS;

use App\Helpers\OS\Users\UserHelper;
use App\Models\OS\EmailSubjects;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Helpers\OS\SettingHelper;
use App\Models\Setting;
use App\Models\User;

class PageElementHelper
{
    public static function EmailLink($emailaddress)
    {
        if(SettingHelper::GetSetting('gmail-per-user') === 'yes' and Auth::user()->GoogleAccessToken != null){
            return  "<a data-toaddress='".$emailaddress."' data-toggle='modal' data-target='#send-gmail'>".$emailaddress."</a>";
        }else{
            if(SettingHelper::GetSetting('gmail-system') != null){
                return  "<a data-toaddress='".$emailaddress."' data-toggle='modal' data-target='#send-gmail'>".$emailaddress."</a>";
            }else{
                if(SettingHelper::GetSetting('email-dont-show-gmail-popup') != null){
                    return "<a href='mailto:".$emailaddress."'>".$emailaddress."</a>";
                }else{
                    return  "<a data-toaddress='".$emailaddress."' data-toggle='modal' data-target='#use-gmail'>".$emailaddress."</a>";
                }
            }
        }
    }

    public static function TableControl($name)
    {
        $element = '<div style="width: 25%; float: left;">'.
                    '<button id="'.$name.'-previous-page" name="'.$name.'-previous-page" type="button" class="btn OS-Button" style="width: 100%;">Previous</button>'.
                    '</div>'.
                    '<div style="width: 50%; float: left; text-align: center;" id="'.$name.'-tableInfo" ></div>'.
                    '<div style="width: 25%; float: left;">'.
                    '<button id="'.$name.'-next-page" name="'.$name.'-next-page" type="button" class="btn OS-Button" style="width: 100%;">Next</button>'.
                    '</div>';
        
        return $element;
    }

    public static function GoogleAddressLink($address)
    {
        $addressstring = $address->AddressString();

        return '<a target="_blank" href="https://maps.google.com/?q='.$addressstring.'">'.$addressstring.'</a>';
        
    }

    public static function Years()
    {
        $now = Carbon::now();
        $years = array();

        array_push($years, $now->year);
        $x = 1;

        while($x <= 10) {
            $now->addYear();
            array_push($years, $now->year);
            $x++;
        }

        return $years;
    }

    public static function Departments()
    {
        if(!isset($users)){
            $users = UserHelper::GetAllUsers();
        }

        $departments = array();

        foreach($users as $user){
            if(!empty($user->department)){
                if(!in_array($user->department, $departments)){
                    $departments[] = $user->department;
                }
            }
        }

        return $departments;
    }

    public static function EmailSubjects(){
        try{
            return EmailSubjects::all();
        }catch(\Throwable $t){
            return "";
        }

    }
}