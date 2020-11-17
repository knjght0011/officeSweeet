<?php

namespace App\Models;

use App\Helpers\OS\SettingHelper;

class ProductLibrary extends CustomBaseModel
{
    protected $table = 'productlibrary';
    
    protected $casts = [
        'stock' => 'integer',
        'reorderlevel' => 'integer',
        'restockto' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function VendorID(){
        if($this->vendor_id === null){
            return "none";
        }else{
            return $this->vendor_id;
        }
    }

    
    public function getCharge() 
    {
        return number_format($this->charge ,2, '.', '') ;
    }
    public function getCost() 
    {
        return number_format($this->cost ,2, '.', '') ;
    }
    
    public function restockcost() 
    {
        return number_format(($this->restockto - $this->stock) * $this->cost,2, '.', '') ;
    }
    
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
    
    public function trackstockwords() 
    {
        if($this->trackstock === 1){
            return "Yes";
        }else{
            return "No";
        }
    }
    
    public function restockamount() 
    {
        return $this->reorderlevel - $this->stock;
    }
    
    public function costtorestock() 
    {
        return $this->restockamount() * $this->cost;
    }
    
    public function stockiftracked() 
    {
        if($this->trackstock === 1){
            return $this->stock;
        }else{
            return "";
        }
    }    
    
    public function reorderleveliftracked() 
    {
        if($this->trackstock === 1){
            return $this->reorderlevel;
        }else{
            return "";
        }
    } 
    
    public function restocktoiftracked() 
    {
        if($this->trackstock === 1){
            return $this->restockto;
        }else{
            return "";
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

    public function NeedsNotification(){

        if($this->stock < 1){
            return true;
        }else{
            if($this->stock <= $this->reorderlevel){
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

    public function CurrentStockValue(){
        return floatval($this->cost * $this->stock);
    }

    public function Type(){

        switch ($this->trackstock) {
            case 0:
                return "Product";
            break;
            case 1:
                return "Product";
            break;
            case 2:
                return "Service";
            break;
        }
    }
}