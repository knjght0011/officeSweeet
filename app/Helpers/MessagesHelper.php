<?php
namespace App\Helpers;

class MessagesHelper
{
    public static function numberOfUnreadParticapants($thread)
    {
        $usersids = $thread->participantsUserIds();
        $number = 0;
        foreach($usersids as $userId){
            if($thread->isUnread($userId)){
                $number++;
            }
        }
        
        return $number;
    }
    
    public static function Test()
    {
        return "test";
    }
    

}
