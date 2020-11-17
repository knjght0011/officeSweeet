<?php

namespace App\Models\OS\PurchaseOrders;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'purchaseorderitems';


    public function purchaseorder()
    {
        return $this->belongsTo('App\Models\OS\PurchaseOrders\PurchaseOrders', 'purchaseorder_id', 'id');
    }


    public function product()
    {
        return $this->belongsTo('App\Models\ProductLibrary', 'product_id', 'id');
    }

    public function Total(){
        return $this->units * $this->unitcost;
    }

    public function UnitsLeft(){
        return $this->units - $this->received;
    }
}
