<?php

namespace App\Models;

class Employee extends CustomBaseModel
{
    //
    
    protected $table = 'employees';
        
    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }
}
