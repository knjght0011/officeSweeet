<?php
namespace App\Models;

class VendorReport extends CustomBaseModel
{
    protected $table = 'vendorreports';
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function client()
    {
        return $this->belongsTo('App\Models\Vendor');
    }
    
    public function getUser() 
    {
        $user_data = $this->user;
        return $user_data->email;
    }
    

}
