<?php

namespace App\Models\Management;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    protected $connection = 'management';

    protected $table = 'promotions';

    protected $dates = ['starts_at', 'expires_at'];

    #protected $casts = [
    #    'parameters' => 'array',
    #];

    public function DealSummery(){
        if($this->numusers === 0){
            return "Unlimited users for $" . $this->costFormated();

        }else{
            return $this->numusers . " users for $" . $this->costFormated();
        }
    }

    public function UserText(){
        if($this->numusers === 0){
            return "Unlimited Users";

        }else{
            return "Up to " . $this->numusers . " Users";
        }
    }

    public function CostText(){
        return "$" . number_format($this->cost, 2, ".", "") . " per month";
    }

    public function costFormated(){
        return number_format($this->cost, 2);
    }

    public function isActive(){
        $now = carbon::now();
        if($this->starts_at->lt($now)){
            if($this->expires_at->gt($now)){
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

}
