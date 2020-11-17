<?php

namespace App\Models\Management;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $connection = 'management';
    
    protected $table = "attachments";
    
    protected $casts = [
    ];
    
    
}