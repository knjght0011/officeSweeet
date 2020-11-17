<?php
namespace App\Helpers\OS;

use Illuminate\Support\Facades\Cache;

use App\Models\Setting;

class SettingHelper
{
    public static function GetSetting($name)
    {
        if (Cache::has('setting-' . $name)) {
            return Cache::get('setting-' . $name);
        }else{
            $setting = Setting::where('name' , $name)->orderBy('created_at', 'desc')->first();
            if(count($setting) === 1){
                if($setting->value === ""){
                    return null;
                }else{
                    Cache::put('setting-' . $setting->name, $setting->value, 5);
                    return $setting->value;
                }
            }else{
                return null;
            }
        }
    }
    
    public static function SetSetting($name, $value)
    {
        $setting = Setting::where('name' , $name)->first();
        
        if(count($setting) === 1){
            $setting->value = $value;
            $setting->save();
        }else{
            $setting = new Setting;
            $setting->name = $name;
            $setting->value = $value;
            $setting->save();
        }
        
        if (Cache::has('setting-' . $name)) {
            Cache::forget('setting-' . $name);
        }
    }

    public static function SaveNewTax($value)
    {
        $setting = new Setting;
        $setting->name = "sales-tax";
        $setting->value = $value;
        $setting->save();

        if (Cache::has('setting-sales-tax')) {
            Cache::forget('setting-sales-tax');
        }
    }

    public static function SaveNewCityTax($value)
    {
        $setting = new Setting;
        $setting->name = "city-tax";
        $setting->value = $value;
        $setting->save();

        if (Cache::has('setting-city-tax')) {
            Cache::forget('setting-city-tax');
        }
    }



    public static function GetSalesTax()
    {
        if (Cache::has('setting-sales-tax')) {
            return Cache::get('setting-sales-tax');
        }else{
            $setting = Setting::where('name' , 'sales-tax')->orderBy('created_at', 'desc')->first();
            if(count($setting) === 1){
                Cache::put('setting-sales-tax', $setting->value, 5);
                return $setting->value;
            }else{
                return "0";
            }
        }
    }

    public static function SetInventoryManager($value)
    {
        $setting = new Setting;
        $setting->name = "inventorymanagerid";
        $setting->value = $value;
        $setting->save();

        if (Cache::has('inventorymanagerid')) {
            Cache::forget('inventorymanagerid');
        }
    }

    public static function GetCityTax()
    {
        if (Cache::has('setting-city-tax')) {
            return Cache::get('setting-city-tax');
        }else{
            $setting = Setting::where('name' , 'city-tax')->orderBy('created_at', 'desc')->first();
            if(count($setting) === 1){
                Cache::put('setting-city-tax', $setting->value, 5);
                return $setting->value;
            }else{
                return "0";
            }
        }
    }

    public static function GetAllSalesTax()
    {
        $setting = Setting::where('name' , 'sales-tax')->get();
        return $setting;
    }

    public static function GetAllCityTax()
    {
        $setting = Setting::where('name' , 'city-tax')->get();
        return $setting;
    }
    
    public static function GetTimeZoneInfo(){
        
        $setting = Setting::where('name' , 'timezone')->first();
        if(count($setting) === 1){
            return self::TimeZoneArray()[$setting->value];
        }else{
            return self::TimeZoneArray()['15'];
        }
    }
    
    private static function TimeZoneArray(){
        return array(
            '1' => array('timeZoneId' => "1", 'gmtAdjustment' => "GMT-12:00", 'useDaylightTime' => "0", 'Adjust' => "-12"),
            '2' => array('timeZoneId' => "2", 'gmtAdjustment' => "GMT-11:00", 'useDaylightTime' => "0", 'Adjust' => "-11"),
            '3' => array('timeZoneId' => "3", 'gmtAdjustment' => "GMT-10:00", 'useDaylightTime' => "0", 'Adjust' => "-10"),
            '4' => array('timeZoneId' => "4", 'gmtAdjustment' => "GMT-09:00", 'useDaylightTime' => "1", 'Adjust' => "-9"),
            '5' => array('timeZoneId' => "5", 'gmtAdjustment' => "GMT-08:00", 'useDaylightTime' => "1", 'Adjust' => "-8"),
            '6' => array('timeZoneId' => "6", 'gmtAdjustment' => "GMT-08:00", 'useDaylightTime' => "1", 'Adjust' => "-8"),
            '7' => array('timeZoneId' => "7", 'gmtAdjustment' => "GMT-07:00", 'useDaylightTime' => "0", 'Adjust' => "-7"),
            '8' => array('timeZoneId' => "8", 'gmtAdjustment' => "GMT-07:00", 'useDaylightTime' => "1", 'Adjust' => "-7"),
            '9' => array('timeZoneId' => "9", 'gmtAdjustment' => "GMT-07:00", 'useDaylightTime' => "1", 'Adjust' => "-7"),
            '10' => array('timeZoneId' => "10", 'gmtAdjustment' => "GMT-06:00", 'useDaylightTime' => "0", 'Adjust' => "-6"),
            '11' => array('timeZoneId' => "11", 'gmtAdjustment' => "GMT-06:00", 'useDaylightTime' => "1", 'Adjust' => "-6"),
            '12' => array('timeZoneId' => "12", 'gmtAdjustment' => "GMT-06:00", 'useDaylightTime' => "1", 'Adjust' => "-6"),
            '13' => array('timeZoneId' => "13", 'gmtAdjustment' => "GMT-06:00", 'useDaylightTime' => "0", 'Adjust' => "-6"),
            '14' => array('timeZoneId' => "14", 'gmtAdjustment' => "GMT-05:00", 'useDaylightTime' => "0", 'Adjust' => "-5"),
            '15' => array('timeZoneId' => "15", 'gmtAdjustment' => "GMT-05:00", 'useDaylightTime' => "1", 'Adjust' => "-5"),
            '16' => array('timeZoneId' => "16", 'gmtAdjustment' => "GMT-05:00", 'useDaylightTime' => "1", 'Adjust' => "-5"),
            '17' => array('timeZoneId' => "17", 'gmtAdjustment' => "GMT-04:00", 'useDaylightTime' => "1", 'Adjust' => "-4"),
            '18' => array('timeZoneId' => "18", 'gmtAdjustment' => "GMT-04:00", 'useDaylightTime' => "0", 'Adjust' => "-4"),
            '19' => array('timeZoneId' => "19", 'gmtAdjustment' => "GMT-04:00", 'useDaylightTime' => "0", 'Adjust' => "-4"),
            '20' => array('timeZoneId' => "20", 'gmtAdjustment' => "GMT-04:00", 'useDaylightTime' => "1", 'Adjust' => "-4"),
            '21' => array('timeZoneId' => "21", 'gmtAdjustment' => "GMT-03:30", 'useDaylightTime' => "1", 'Adjust' => "-3.5"),
            '22' => array('timeZoneId' => "22", 'gmtAdjustment' => "GMT-03:00", 'useDaylightTime' => "1", 'Adjust' => "-3"),
            '23' => array('timeZoneId' => "23", 'gmtAdjustment' => "GMT-03:00", 'useDaylightTime' => "0", 'Adjust' => "-3"),
            '24' => array('timeZoneId' => "24", 'gmtAdjustment' => "GMT-03:00", 'useDaylightTime' => "1", 'Adjust' => "-3"),
            '25' => array('timeZoneId' => "25", 'gmtAdjustment' => "GMT-03:00", 'useDaylightTime' => "1", 'Adjust' => "-3"),
            '26' => array('timeZoneId' => "26", 'gmtAdjustment' => "GMT-02:00", 'useDaylightTime' => "1", 'Adjust' => "-2"),
            '27' => array('timeZoneId' => "27", 'gmtAdjustment' => "GMT-01:00", 'useDaylightTime' => "0", 'Adjust' => "-1"),
            '28' => array('timeZoneId' => "28", 'gmtAdjustment' => "GMT-01:00", 'useDaylightTime' => "1", 'Adjust' => "-1"),
            '29' => array('timeZoneId' => "29", 'gmtAdjustment' => "GMT+00:00", 'useDaylightTime' => "0", 'Adjust' => "0"),
            '30' => array('timeZoneId' => "30", 'gmtAdjustment' => "GMT+00:00", 'useDaylightTime' => "1", 'Adjust' => "0"),
            '31' => array('timeZoneId' => "31", 'gmtAdjustment' => "GMT+01:00", 'useDaylightTime' => "1", 'Adjust' => "1"),
            '32' => array('timeZoneId' => "32", 'gmtAdjustment' => "GMT+01:00", 'useDaylightTime' => "1", 'Adjust' => "1"),
            '33' => array('timeZoneId' => "33", 'gmtAdjustment' => "GMT+01:00", 'useDaylightTime' => "1", 'Adjust' => "1"),
            '34' => array('timeZoneId' => "34", 'gmtAdjustment' => "GMT+01:00", 'useDaylightTime' => "1", 'Adjust' => "1"),
            '35' => array('timeZoneId' => "35", 'gmtAdjustment' => "GMT+01:00", 'useDaylightTime' => "1", 'Adjust' => "1"),
            '36' => array('timeZoneId' => "36", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '37' => array('timeZoneId' => "37", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '38' => array('timeZoneId' => "38", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '39' => array('timeZoneId' => "39", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '40' => array('timeZoneId' => "40", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "0", 'Adjust' => "2"),
            '41' => array('timeZoneId' => "41", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '42' => array('timeZoneId' => "42", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '43' => array('timeZoneId' => "43", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '44' => array('timeZoneId' => "44", 'gmtAdjustment' => "GMT+02:00", 'useDaylightTime' => "1", 'Adjust' => "2"),
            '45' => array('timeZoneId' => "45", 'gmtAdjustment' => "GMT+03:00", 'useDaylightTime' => "0", 'Adjust' => "3"),
            '46' => array('timeZoneId' => "46", 'gmtAdjustment' => "GMT+03:00", 'useDaylightTime' => "1", 'Adjust' => "3"),
            '47' => array('timeZoneId' => "47", 'gmtAdjustment' => "GMT+03:00", 'useDaylightTime' => "0", 'Adjust' => "3"),
            '48' => array('timeZoneId' => "48", 'gmtAdjustment' => "GMT+03:00", 'useDaylightTime' => "0", 'Adjust' => "3"),
            '49' => array('timeZoneId' => "49", 'gmtAdjustment' => "GMT+03:30", 'useDaylightTime' => "1", 'Adjust' => "3.5"),
            '50' => array('timeZoneId' => "50", 'gmtAdjustment' => "GMT+04:00", 'useDaylightTime' => "0", 'Adjust' => "4"),
            '51' => array('timeZoneId' => "51", 'gmtAdjustment' => "GMT+04:00", 'useDaylightTime' => "1", 'Adjust' => "4"),
            '52' => array('timeZoneId' => "52", 'gmtAdjustment' => "GMT+04:00", 'useDaylightTime' => "1", 'Adjust' => "4"),
            '53' => array('timeZoneId' => "53", 'gmtAdjustment' => "GMT+04:30", 'useDaylightTime' => "0", 'Adjust' => "4.5"),
            '54' => array('timeZoneId' => "54", 'gmtAdjustment' => "GMT+05:00", 'useDaylightTime' => "1", 'Adjust' => "5"),
            '55' => array('timeZoneId' => "55", 'gmtAdjustment' => "GMT+05:00", 'useDaylightTime' => "0", 'Adjust' => "5"),
            '56' => array('timeZoneId' => "56", 'gmtAdjustment' => "GMT+05:30", 'useDaylightTime' => "0", 'Adjust' => "5.5"),
            '57' => array('timeZoneId' => "57", 'gmtAdjustment' => "GMT+05:30", 'useDaylightTime' => "0", 'Adjust' => "5.5"),
            '58' => array('timeZoneId' => "58", 'gmtAdjustment' => "GMT+05:45", 'useDaylightTime' => "0", 'Adjust' => "5.75"),
            '59' => array('timeZoneId' => "59", 'gmtAdjustment' => "GMT+06:00", 'useDaylightTime' => "1", 'Adjust' => "6"),
            '60' => array('timeZoneId' => "60", 'gmtAdjustment' => "GMT+06:00", 'useDaylightTime' => "0", 'Adjust' => "6"),
            '61' => array('timeZoneId' => "61", 'gmtAdjustment' => "GMT+06:30", 'useDaylightTime' => "0", 'Adjust' => "6.5"),
            '62' => array('timeZoneId' => "62", 'gmtAdjustment' => "GMT+07:00", 'useDaylightTime' => "0", 'Adjust' => "7"),
            '63' => array('timeZoneId' => "63", 'gmtAdjustment' => "GMT+07:00", 'useDaylightTime' => "1", 'Adjust' => "7"),
            '64' => array('timeZoneId' => "64", 'gmtAdjustment' => "GMT+08:00", 'useDaylightTime' => "0", 'Adjust' => "8"),
            '65' => array('timeZoneId' => "65", 'gmtAdjustment' => "GMT+08:00", 'useDaylightTime' => "0", 'Adjust' => "8"),
            '66' => array('timeZoneId' => "66", 'gmtAdjustment' => "GMT+08:00", 'useDaylightTime' => "0", 'Adjust' => "8"),
            '67' => array('timeZoneId' => "67", 'gmtAdjustment' => "GMT+08:00", 'useDaylightTime' => "0", 'Adjust' => "8"),
            '68' => array('timeZoneId' => "68", 'gmtAdjustment' => "GMT+08:00", 'useDaylightTime' => "0", 'Adjust' => "8"),
            '69' => array('timeZoneId' => "69", 'gmtAdjustment' => "GMT+09:00", 'useDaylightTime' => "0", 'Adjust' => "9"),
            '70' => array('timeZoneId' => "70", 'gmtAdjustment' => "GMT+09:00", 'useDaylightTime' => "0", 'Adjust' => "9"),
            '71' => array('timeZoneId' => "71", 'gmtAdjustment' => "GMT+09:00", 'useDaylightTime' => "1", 'Adjust' => "9"),
            '72' => array('timeZoneId' => "72", 'gmtAdjustment' => "GMT+09:30", 'useDaylightTime' => "0", 'Adjust' => "9.5"),
            '73' => array('timeZoneId' => "73", 'gmtAdjustment' => "GMT+09:30", 'useDaylightTime' => "0", 'Adjust' => "9.5"),
            '74' => array('timeZoneId' => "74", 'gmtAdjustment' => "GMT+10:00", 'useDaylightTime' => "0", 'Adjust' => "10"),
            '75' => array('timeZoneId' => "75", 'gmtAdjustment' => "GMT+10:00", 'useDaylightTime' => "1", 'Adjust' => "10"),
            '76' => array('timeZoneId' => "76", 'gmtAdjustment' => "GMT+10:00", 'useDaylightTime' => "1", 'Adjust' => "10"),
            '77' => array('timeZoneId' => "77", 'gmtAdjustment' => "GMT+10:00", 'useDaylightTime' => "0", 'Adjust' => "10"),
            '78' => array('timeZoneId' => "78", 'gmtAdjustment' => "GMT+10:00", 'useDaylightTime' => "1", 'Adjust' => "10"),
            '79' => array('timeZoneId' => "79", 'gmtAdjustment' => "GMT+11:00", 'useDaylightTime' => "1", 'Adjust' => "11"),
            '80' => array('timeZoneId' => "80", 'gmtAdjustment' => "GMT+12:00", 'useDaylightTime' => "1", 'Adjust' => "12"),
            '81' => array('timeZoneId' => "81", 'gmtAdjustment' => "GMT+12:00", 'useDaylightTime' => "0", 'Adjust' => "12"),
            '82' => array('timeZoneId' => "82", 'gmtAdjustment' => "GMT+13:00", 'useDaylightTime' => "0", 'Adjust' => "13"),
            );
    }
}