<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class CustomTabData extends Model
{
    protected $table = 'customtabsdata';

    protected $casts = [
        'data' => 'array',
    ];


}
