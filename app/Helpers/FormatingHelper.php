<?php
namespace App\Helpers;

use App\Helpers\OS\Users\UserHelper;
use App\Models\User;

use Carbon\Carbon;

class FormatingHelper
{
    public static function DateTimeWords(Carbon $date)
    {
        return $date->format('jS M Y g:i a');
    }
    
    public static function DateTimeWordsNoTime(Carbon $date)
    {
        return $date->format('jS M Y');
    }
    
    public static function DateTimeISO(Carbon $date)
    {
        return $date->format('Y-m-d H:i');
    }
    
    #Wednesday 14th June, 2017 05:45
    public static function DateTimeTimeSheet(Carbon $date)
    {    
        return $date->format('l jS M, Y H:i');
    }
    
    public static function DateISO(Carbon $date)
    {
        return $date->format('Y-m-d');
    }

    public static function TodayISO()
    {
        return Carbon::now()->format('Y-m-d');
    }
    
    public static function UsersNamesArray()
    {
        $usernames = array();
        $users = UserHelper::GetAllUsers(); //User::where('id', '>', 0)->where('os_support_permission', 0)->get();
        foreach($users as $user){
            $usernames[$user->id] = $user->name;
        }
        return $usernames;
    }
}
