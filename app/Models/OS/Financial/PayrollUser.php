<?php
namespace App\Models\OS\Financial;

use Illuminate\Database\Eloquent\Model;

class PayrollUser extends Model
{
     protected $table = 'payrollusers';
     
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            // ... code here
        });

        self::created(function($model){
            // ... code here
        });

        self::saving(function($model){
            $model->total = floatval($model->netpay) * intval($model->units);
        });

        self::updated(function($model){
            // ... code here
        });

        self::deleting(function($model){
            if($model->final === 1){
                return false;
            }
        });

        self::deleted(function($model){
            // ... code here
        });
    } 
     
    public function payroll()
    {
        return $this->belongsTo('App\Models\OS\Financial\Payroll');
    }
    
    public function TaxWords(){
        
        if($this->taxable ===  1){
            return "Yes";
        }else{
            return "No";
        }
    }
    

    
}
