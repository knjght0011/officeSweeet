<?php

namespace App\Models\OS;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $connection = 'subdomain';
    protected $table = 'passwordreset';
    
    public function setTokenAttribute($value)
    {
        if($value === null){
            $this->attributes['token']  = null;
        }else{
            $length = 255;

            $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
                      '0123456789';

            $str = '';
            $max = strlen($chars) - 1;

            for ($i=0; $i < $length; $i++){
              $str .= $chars[random_int(0, $max)];
            }

            $this->attributes['token']  = $str;
        }
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
