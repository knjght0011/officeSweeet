<?php

namespace App\Models;

class QuoteItem extends CustomBaseModel
{
    protected $table = 'quoteitems';

    public function quote()
    {
        return $this->belongsTo('App\Models\Quote', 'quote_id', 'id');
    }

    public function OldGetproduct()
    {
       return $product = ProductLibrary::where('SKU' , '=' , $this->SKU)->first();
    }

    public function productlibrary()
    {
        return $this->belongsTo('App\Models\ProductLibrary', 'productlibrary_id', 'id' );
    }

    public function servicelibrary()
    {
        return $this->belongsTo('App\Models\OS\Inventory\ServiceLibrary', 'service_libraries_id', 'id');
    }

    public function billablehours()
    {
        return $this->belongsTo('App\Models\OS\BillableHour');
    }

    public function receipts()
    {
        return $this->belongsTo('App\Models\Receipt');
    }

    public function getSubTotal()
    {
        return number_format($this->getSubTotalRAW() , 2, '.', '');
    }

    public function getTax()
    {
        return number_format($this->getTaxRAW() , 2, '.', '');
    }

    public function getCityTax()
    {
        return number_format($this->getCityTaxRAW() , 2, '.', '');
    }

    public function getTotal()
    {
        return number_format($this->getTotalRAW() , 2, '.', '');
    }

    public function getSubTotalRAW()
    {
        return $this->costperunit * $this->units;
    }

    public function getTaxRAW()
    {
        return round (($this->getSubTotalRAW() / 100) * $this->tax, 2);
    }

    public function getCityTaxRAW()
    {
        return round (($this->getSubTotalRAW() / 100) * $this->citytax, 2);
    }
    
    public function getTotalRAW() 
    {
        $total = $this->getSubTotalRAW();
        $total += $this->getTaxRAW();
        $total += $this->getCityTaxRAW();
        return $total;
    }

    public function getProduct()
    {
        return $this->productlibrary();
    }

    public function getProductStatus() 
    {
        $product = $this->getProduct();
        if($product !== false){
            if($product->trackstock === 1){
                if($this->units <= $product->stock){
                    return "In Stock.";
                }else if($this->units > $product->stock){
                    return "Out of stock.";
                }
            }else{
                return "Stock not tracked on this product.";
            }
        }else{
            return false;
        }
    }

    public function getProductAction()
    {
        $product = $this->getProduct();
        if($product !== false){
            if($product->trackstock === 1){
                if($this->units <= $product->stock){
                    return "Reduce Stock to " . ($product->stock - $this->units);
                }else if($this->units > $product->stock){
                    return "Put Stock On Back Order.";
                }
            }else{
                "No Action.";
            }
        }else{
            return "No Action.";
        }
    }

    public function getStock()
    {
        if($this->productlibrary_id != null){
            if($this->productlibrary->trackstock === 1){
                return $this->productlibrary->stock;
            }else{
                return "Product Not held in Inventory";
            }
        }else{
            return "Not Product";
        }
    }

    public function ReduceStock()
    {
        $product = $this->OldGetproduct();
        if($product !== false){
            if($product->trackstock === 1){
                $product->stock = intval($product->stock) - intval($this->units);
                $product->save();
            }
        }else{
            return false;
        }
    }

    public function getVendor()
    {
        if($this->productlibrary_id != null){
            if($this->productlibrary->vendor_id === null){
                return "No Vendor Set";
            }else{
                return $this->productlibrary->vendor-> getName();
            }
        }else{
            return "Not Product";
        }
    }

    public function getVendorID()
    {
        if($this->productlibrary_id != null){
            if($this->productlibrary->vendor_id === null){
                return "No Vendor Set";
            }else{
                return $this->productlibrary->vendor->id;
            }
        }else{
            return "Not Product";
        }
    }

    public function getQuoteNumber(){
        return $this->quote->quotenumber;
    }

    public function LinkType(){
        if($this->productlibrary_id != null){
            return "Product";
        }
        if($this->service_libraries_id != null){
            return "Service";
        }
        if($this->billablehours_id != null){
            return "Billable Hours";
        }
        if($this->receipts_id != null){
            return "Expense";
        }
        return "Unknown";
    }

    public function LinkId(){
        if($this->productlibrary_id != null){
            return $this->productlibrary_id;
        }
        if($this->service_libraries_id != null){
            return $this->service_libraries_id;
        }
        if($this->billablehours_id != null){
            return $this->billablehours_id;
        }
        if($this->receipts_id != null){
            return $this->receipts_id;
        }
        return 0;
    }

    public function getCost(){

        if($this->productlibrary_id != null){
            return $this->productlibrary->cost * $this->units;
        }
        if($this->billablehours_id != null){
            return 0.00;
        }
        if($this->receipts_id != null){
            return 0.00;
        }
        if($this->service_libraries_id != null){
            return $this->servicelibrary->cost * $this->units;
        }
        return 0.00;
    }


}
