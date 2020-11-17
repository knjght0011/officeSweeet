<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $connection = 'management';
    
    protected $table = "alerts";
   
    protected $casts = [
        'variables' => 'array',
    ];
        
    public function account()
    {
        return $this->belongsTo('App\Models\Management\Account' , 'account_id', 'id' );
    }

    public function GetAccount()
    {
        $account = $this->account;
        if($account === null){
            $unknownaccount = new Account;
            $unknownaccount->subdomain = "Unknown";
            return $unknownaccount;
        }else{
            return $account;
        }
    }
    
    public function ActionStageWord()
    {
        switch ($this->action_stage) {
            case null:
                return "No Action Required";
            case "1":
                return "Action Required";
            case "2":
                return "Action Done";
            default;
                return "unknown";
        }
    }
     
}
