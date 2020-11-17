<?php

namespace App\Models\OS;

use Illuminate\Database\Eloquent\Model;

class BillableHour extends Model
{
    protected $connection = 'subdomain';
    protected $table = 'billablehours';

    public function client()
    {
        return $this->belongsTo('App\Models\Client' , 'client_id', 'id' )->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User' , 'user_id', 'id');
    }

    public function Total(){
        return $this->rate * $this->hours;
    }

    public function Description(){

        return floatval($this->hours) . " Hours At $" . number_format($this->rate , 2, '.', '') . " Per Hour By " . $this->user->getName();

    }


}
