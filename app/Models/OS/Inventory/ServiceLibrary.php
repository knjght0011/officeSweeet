<?php

namespace App\Models\OS\Inventory;

use App\Helpers\OS\SettingHelper;

use Illuminate\Database\Eloquent\Model;

class ServiceLibrary extends Model
{
    //protected $table = "service_libraries";

    public function taxablewords()
    {
        if($this->taxable === 1){
            return "Yes";
        }else if($this->taxable === 0){
            return "No";
        }else{
            return "error";
        }
    }

    public function Tax(){
        if($this->taxable === 1){
            return SettingHelper::GetSalesTax();
        }else{
            return 0;
        }
    }

    public function CityTax(){
        if($this->taxable === 1){
            return SettingHelper::GetCityTax();
        }else{
            return 0;
        }
    }
}
