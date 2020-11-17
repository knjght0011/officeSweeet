<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\TransnationalHelper;



class Subscription extends Model
{

    use SoftDeletes;

    protected $connection = 'management';
    
    protected $table = "subscriptions";

    protected $fillable = array('subscription_id');

    #protected $dates = ['active'];

    public function account()
    {
        return $this->belongsTo('App\Models\Management\Account' , 'account_id', 'id' );
    }

    public function TNSubscription(){
        if($this->subscription_id != null){
            return TransnationalHelper::GetSubscriptionID($this->subscription_id);
        }else{
            return false;
        }

    }

}
