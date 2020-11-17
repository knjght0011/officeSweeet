<?php
namespace App\Helpers\OS\UI;

use App\Helpers\OS\SettingHelper;

class TextHelper
{

    public static function GetText($type){

        switch (SettingHelper::GetSetting('system-type')) {
            case "NonProfit":
                return self::GetNonProfit($type);
                break;
            default:
                return $type;
        }

    }

    public static function GetNonProfit($type){

        $replace = array(
            "Clients" => "Members",
            "Client" => "Member",
            "Quotes" => "Pledges",
            "Quote" => "Pledge",
            "Profit and Loss" => "Income",
            //"" => "",
            );

        if(isset($replace[$type])){
            return $replace[$type];
        }else{
            return $type;
        }

    }

    public static function GetLogo(){

        switch (SettingHelper::GetSetting('system-type')) {
            case "NonProfit":
                return "/images/oslogo-non-profit.png";
                break;
            default:
                return "/images/oslogo.png";
        }

    }

}