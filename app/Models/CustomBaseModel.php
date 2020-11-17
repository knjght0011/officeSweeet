<?php
namespace App\Models;

use App\Helpers\FormatingHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;

//use \App\Providers\EventLog;

class CustomBaseModel extends Eloquent
{
    use SoftDeletes;
      
    protected $connection = 'subdomain';
    /**
     * Display timestamps in user's timezone
    */ 
    protected function asDateTime($value)
    {
        $format = "Y-m-d";
        if(date($format, strtotime($value)) == date($value)) {
            if ($value instanceof Carbon) {

                 return $value;
            }
            $me = \Auth::user();
            $value = parent::asDateTime($value);
            return $value;
        } else {
            if ($value instanceof Carbon) {

                 return $value;
             }

            $value = parent::asDateTime($value);

            if (\Auth::check()) {
                $me = \Auth::user();
                return $value->addMinutes(intval($me->timezoneoffset) * -1);
            }else{
                return $value;
            }

        }   
    }

    public function formatDate_updated_at() 
    {
        return FormatingHelper::DateTimeWords($this->updated_at);
    }

    public function formatDate_updated_at_iso()
    {
        return FormatingHelper::DateISO($this->updated_at);
    }

    public function formatDateTime_updated_at_iso()
    {
        return FormatingHelper::DateTimeISO($this->updated_at);
    }

    public function formatDate_created_at() 
    {
        return FormatingHelper::DateTimeWords($this->created_at);
    }    
    
    public function formatDate_created_at_no_time() 
    {
        return FormatingHelper::DateTimeWordsNoTime($this->created_at);
    }  
    
    public function formatDate_created_at_iso() 
    {
        return FormatingHelper::DateISO($this->created_at);
    }

    public function formatDateTime_created_at_iso()
    {
        return FormatingHelper::DateTimeISO($this->created_at);
    }
    /*
    protected function asDateTime($value)
    {
        if ($value instanceof Carbon) {
            return $value;
        }
        #EventLog::add('USED');
        $me = \Auth::user();
        $value = parent::asDateTime($value);
        
        
        switch ($me->timezone) {
            case "0":#(UTC-12:00) International Date Line West
                $hours = -12;
                return $value->addHours($hours);
            case "110":#(UTC-11:00) Coordinated Universal Time -11
                $hours = -11;
                return $value->addHours($hours);
            case "200":#(UTC-10:00) Hawaii
                $hours = -10;
                return $value->addHours($hours);
            case "300":#(UTC-09:00) Alaska
                $hours = -9;
                return $value->addHours($hours);
            case "400":#(UTC-08:00) Pacific Time (US and Canada)
                $hours = -8;
                return $value->addHours($hours);
            case "410":#(UTC-08:00)Baja California
                $hours = -8;
                return $value->addHours($hours);
            case "500":#(UTC-07:00) Mountain Time (US and Canada)
                $hours = -7;
                return $value->addHours($hours);
            case "510":#(UTC-07:00) Chihuahua, La Paz, Mazatlan
                $hours = -7;
                return $value->addHours($hours);
            case "520":#(UTC-07:00) Arizona
                $hours = -7;
                return $value->addHours($hours);
            case "600":#(UTC-06:00) Saskatchewan
                $hours = -6;
                return $value->addHours($hours);
            case "610":#(UTC-06:00) Central America
                $hours = -6;
                return $value->addHours($hours);
            case "620":#(UTC-06:00) Central Time (US and Canada)
                $hours = -6;
                return $value->addHours($hours);
            case "630":#(UTC-06:00) Guadalajara, Mexico City, Monterrey
                $hours = -6;
                return $value->addHours($hours);
            case "700":#(UTC-05:00) Eastern Time (US and Canada)
                $hours = -5;
                return $value->addHours($hours);
            case "710":#(UTC-05:00) Bogota, Lima, Quito
                $hours = -5;
                return $value->addHours($hours);
            case "720":#(UTC-05:00) Indiana (East)
                $hours = -5;
                return $value->addHours($hours);
            case "840":#(UTC-04:30) Caracas
                $hours = -4;
                $mins = -30;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "800":#(UTC-04:00) Atlantic Time (Canada)
                $hours = -4;
                return $value->addHours($hours);
            case "810":#(UTC-04:00) Cuiaba
                $hours = -4;
                return $value->addHours($hours);
            case "820":#(UTC-04:00) Santiago
                $hours = -4;
                return $value->addHours($hours);
            case "830":#(UTC-04:00) Georgetown, La Paz, Manaus, San Juan
                $hours = -4;
                return $value->addHours($hours);
            case "850":#(UTC-04:00) Asuncion
                $hours = -4;
                return $value->addHours($hours);
            case "900":#(UTC-03:30) Newfoundland
                $hours = -3;
                $mins = -30;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "910":#(UTC-03:00) Brasilia
                $hours = -3;
                return $value->addHours($hours);
            case "920":#(UTC-03:00) Greenland
                $hours = -3;
                return $value->addHours($hours);
            case "930":#(UTC-03:00) Montevideo
                $hours = -3;
                return $value->addHours($hours);
            case "940":#(UTC-03:00) Cayenne, Fortaleza
                $hours = -3;
                return $value->addHours($hours);
            case "950":#(UTC-03:00) Buenos Aires
                $hours = -3;
                return $value->addHours($hours);
            case "1000":#(UTC-02:00) Mid-Atlantic
                $hours = -2;
                return $value->addHours($hours);
            case "1010":#(UTC-02:00) Coordinated Universal Time -02
                $hours = -2;
                return $value->addHours($hours);
            case "1100":#(UTC-01:00) Azores
                $hours = -1;
                return $value->addHours($hours);
            case "1110":#(UTC-01:00) Cabo Verde Is.
                $hours = -1;
                return $value->addHours($hours);
            case "1200":#(UTC) Dublin, Edinburgh, Lisbon, London
                $hours = 0;
                return $value->addHours($hours);
            case "1210":#(UTC) Monrovia, Reykjavik
                $hours = 0;
                return $value->addHours($hours);
            case "1220":#(UTC) Casablanca
                $hours = 0;
                return $value->addHours($hours);
            case "1230":#(UTC) Coordinated Universal Time
                $hours = 0;
                return $value->addHours($hours);
            case "1300":#(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague
                $hours = 1;
                return $value->addHours($hours);
            case "1310":#(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb
                $hours = 1;
                return $value->addHours($hours);
            case "1320":#(UTC+01:00) Brussels, Copenhagen, Madrid, Paris
                $hours = 1;
                return $value->addHours($hours);
            case "1330":#(UTC+01:00) West Central Africa
                $hours = 1;
                return $value->addHours($hours);
            case "1340":#(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna
                $hours = 1;
                return $value->addHours($hours);
            case "1350":#(UTC+01:00) Windhoek
                $hours = 1;
                return $value->addHours($hours);
            case "1400":#(UTC+02:00) Minsk
                $hours = 2;
                return $value->addHours($hours);
            case "1410":#(UTC+02:00) Cairo
                $hours = 2;
                return $value->addHours($hours);
            case "1420":#(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius
                $hours = 2;
                return $value->addHours($hours);
            case "1430":#(UTC+02:00) Athens, Bucharest
                $hours = 2;
                return $value->addHours($hours);
            case "1440":#(UTC+02:00) Jerusalem
                $hours = 2;
                return $value->addHours($hours);
            case "1450":#(UTC+02:00) Amman
                $hours = 2;
                return $value->addHours($hours);
            case "1460":#(UTC+02:00) Beirut
                $hours = 2;
                return $value->addHours($hours);
            case "1470":#(UTC+02:00) Harare, Pretoria
                $hours = 2;
                return $value->addHours($hours);
            case "1480":#(UTC+02:00) Damascus
                $hours = 2;
                return $value->addHours($hours);
            case "1490":#(UTC+02:00) Istanbul
                $hours = 2;
                return $value->addHours($hours);
            case "1500":#(UTC+03:00) Kuwait, Riyadh
                $hours = 3;
                return $value->addHours($hours);
            case "1510":#(UTC+03:00) Baghdad
                $hours = 3;
                return $value->addHours($hours);
            case "1520":#(UTC+03:00) Nairobi
                $hours = 3;
                return $value->addHours($hours);
            case "1530":#(UTC+03:00) Kaliningrad
                $hours = 3;
                return $value->addHours($hours);
            case "1550":#(UTC+03:30) Tehran
                $hours = 3;
                $mins = 30;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "1540":#(UTC+04:00) Moscow, St. Petersburg, Volgograd
                $hours = 4;
                return $value->addHours($hours);
            case "1600":#(UTC+04:00) Abu Dhabi, Muscat
                $hours = 4;
                return $value->addHours($hours);
            case "1610":#(UTC+04:00) Baku
                $hours = 4;
                return $value->addHours($hours);
            case "1620":#(UTC+04:00) Yerevan
                $hours = 4;
                return $value->addHours($hours);
            case "1640":#(UTC+04:00) Tbilisi
                $hours = 4;
                return $value->addHours($hours);
            case "1650":#(UTC+04:00) Port Louis
                $hours = 4;
                return $value->addHours($hours);
            case "1630":#(UTC+04:30) Kabul
                $hours = 4;
                $mins = 30;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "1710":#(UTC+05:00) Tashkent
                $hours = 5;
                return $value->addHours($hours);
            case "1750":#(UTC+05:00) Islamabad, Karachi
                $hours = 5;
                return $value->addHours($hours);
            case "1720":#(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi
                $hours = 5;
                $mins = 30;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "1730":#(UTC+05:30) Sri Jayawardenepura
                $hours = 5;
                $mins = 30;
                $value->addHours($hours); 
                $value->addMinutes($hours);
                return $value;
            case "1740":#(UTC+05:45) Kathmandu
                $hours = 5;
                $mins = 45;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "1700":#(UTC+06:00) Ekaterinburg
                $hours = 6;
                return $value->addHours($hours);
            case "1800":#(UTC+06:00) Astana
                $hours = 6;
                return $value->addHours($hours);
            case "1830":#(UTC+06:00) Dhaka
                $hours = 6;
                return $value->addHours($hours);
            case "1820":#(UTC+06:30) Yangon (Rangoon)
                $hours = 6;
                $mins = 30;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "1810":#(UTC+07:00) Novosibirsk
                $hours = 7;
                return $value->addHours($hours);
            case "1910":#(UTC+07:00) Bangkok, Hanoi, Jakarta
                $hours = 7;
                return $value->addHours($hours);
            case "1900":#(UTC+08:00) Krasnoyarsk
                $hours = 8;
                return $value->addHours($hours);
            case "2000":#(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi
                $hours = 8;
                return $value->addHours($hours);
            case "2020":#(UTC+08:00) Kuala Lumpur, Singapore
                $hours = 8;
                return $value->addHours($hours);
            case "2030":#(UTC+08:00) Taipei
                $hours = 8;
                return $value->addHours($hours);
            case "2040":#(UTC+08:00) Perth
                $hours = 8;
                return $value->addHours($hours);
            case "2050":#(UTC+08:00) Ulaanbaatar
                $hours = 8;
                return $value->addHours($hours);
            case "2010":#(UTC+09:00) Irkutsk
                $hours = 9;
                return $value->addHours($hours);
            case "2100":#(UTC+09:00) Seoul
                $hours = 9;
                return $value->addHours($hours);
            case "2110":#(UTC+09:00) Osaka, Sapporo, Tokyo
                $hours = 9;
                return $value->addHours($hours);
            case "2130":#((UTC+09:30) Darwin
                $hours = 9;
                $mins = 30;
                $value->addHours($hours);
                $value->addMinutes($mins);
                return $value;
            case "2140":#(UTC+09:30) Adelaide
                $hours = 9;
                $mins = 30;
                $value->addHours($hours); 
                $value->addMinutes($mins);
                return $value;
            case "2120":#(UTC+10:00) Yakutsk
                $hours = 10;
                return $value->addHours($hours);
            case "2200":#(UTC+10:00) Canberra, Melbourne, Sydney
                $hours = 10;
                return $value->addHours($hours);
            case "2210":#(UTC+10:00) Brisbane
                $hours = 10;
                return $value->addHours($hours);
            case "2220":#(UTC+10:00) Hobart
                $hours = 10;
                return $value->addHours($hours);
            case "2240":#(UTC+10:00) Guam, Port Moresby
                $hours = 10;
                return $value->addHours($hours);
            case "2230":#(UTC+11:00) Vladivostok
                $hours = 11;
                return $value->addHours($hours);
            case "2300":#(UTC+11:00) Solomon Is., New Caledonia
                $hours = 11;
                return $value->addHours($hours);
            case "2310":#(UTC+12:00) Magadan
                $hours = 12;
                return $value->addHours($hours);
            case "2400":#(UTC+12:00) Fiji
                $hours = 12;
                return $value->addHours($hours);
            case "2410":#(UTC+12:00) Auckland, Wellington
                $hours = 12;
                return $value->addHours($hours);
            case "2430":#(UTC+12:00) Coordinated Universal Time +12
                $hours = 12;
                return $value->addHours($hours);
            case "2500":#(UTC+13:00) Nuku'alofa
                $hours = 13;
                return $value->addHours($hours);
            case "2510":#(UTC-11:00)Samoa
                $hours = -11;
                return $value->addHours($hours);
            default:
                $hours = 0;
                return $value->addHours($hours);

        }
    }
  
     */
}