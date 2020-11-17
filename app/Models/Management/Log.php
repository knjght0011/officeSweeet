<?php

namespace App\Models\Management;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Log extends Model
{
    protected $table = 'logs';

    protected $connection = 'deployment';

    protected $casts = [
        'context' => 'array',
    ];
    
    public function messageFirstLine() 
    {
        $sub = explode("\n", $this->message);
        return $sub[0];
    }
    
    public function getUser() 
    {
        #$user = User::find($this->created_by);
        return null;#$user;
    }
    
}
