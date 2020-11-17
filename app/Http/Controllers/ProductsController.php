<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;


use \App\Providers\EventLog;

use App\Models\ProductLibrary;

use App\Helpers\OS\SettingHelper;

class ProductsController extends Controller
{

    public function SaveProduct()
    {
        $productdata = array(
            'id' => Input::get('id'),
            'productname' => Input::get('productname'),
            'sku' => Input::get('sku'),
            'upc' => Input::get('upc'),
            'charge' => Input::get('charge'),
            'cost' => Input::get('cost'),
            'taxable' => boolval(Input::get('taxable')),
            'billingfrequency' => Input::get('billingfrequency'),
            'stock' => Input::get('stock'),
            'reorderlevel' => Input::get('reorderlevel'),
            'restockto' => Input::get('restockto'),
            'trackstock' => Input::get('trackstock'),
            'companyuse' => Input::get('companyuse'),
            'vendorid' => Input::get('vendorid'),
            'vendorref' => Input::get('vendorref'),
            'location' => Input::get('location'),
            'category' => Input::get('category'),
        );

        if($productdata['trackstock'] === 0){
            $productdata['companyuse'] = 0;
        }

        $validator = $this->ValidateProductInput($productdata);

        if ($validator->fails()){

            return ['status' => 'validation', 'errors' => $validator->errors()->toArray()];

        } else {

            if ($productdata['id'] == 0)
            {

                //create new product
                $product = new ProductLibrary;
                $product->productname = $productdata['productname'];
                $product->sku = $productdata['sku'];
                $product->upc = $productdata['upc'];
                $product->charge = $productdata['charge'];
                $product->cost = $productdata['cost'];
                $product->taxable = $productdata['taxable'];
                $product->billingfrequency = $productdata['billingfrequency'];
                $product->stock = $productdata['stock'];
                $product->reorderlevel = $productdata['reorderlevel'];
                $product->restockto = $productdata['restockto'];
                $product->trackstock = $productdata['trackstock'];
                $product->companyuse = $productdata['companyuse'];

                $product->vendorref = $productdata['vendorref'];
                $product->vendor_id = $productdata['vendorid'];

                $product->location = $productdata['location'];
                $product->category = $productdata['category'];

                $product->save();

                EventLog::add('New product created ID:'.$product->id.' Name:'.$product->productname);

                return ['status' => 'OK', 'id' => $product->id, 'product' => $product, 'mode' => 'new'];

            } else {

                //update old product
                $product = ProductLibrary::find($productdata['id']);
                $product->productname = $productdata['productname'];
                $product->sku = $productdata['sku'];
                $product->upc = $productdata['upc'];
                $product->charge = $productdata['charge'];
                $product->cost = $productdata['cost'];
                $product->taxable = $productdata['taxable'];
                $product->billingfrequency = $productdata['billingfrequency'];
                $product->stock = $productdata['stock'];
                $product->reorderlevel = $productdata['reorderlevel'];
                $product->restockto = $productdata['restockto'];
                $product->trackstock = $productdata['trackstock'];
                $product->companyuse = $productdata['companyuse'];

                $product->vendorref = $productdata['vendorref'];
                $product->vendor_id = $productdata['vendorid'];

                $product->location = $productdata['location'];
                $product->category = $productdata['category'];

                $product->save();

                EventLog::add('Updated product ID:'.$product->id.' Name:'.$product->productname);

                return ['status' => 'OK', 'id' => $product->id, 'product' => $product, 'mode' => 'edit'];

            }
        }
    }
    public function SaveProductTax()
    {
        $tax = Input::get('tax');

        SettingHelper::SaveNewTax($tax);

        return "done";

    }
    public function SaveProductCityTax()
    {
        $tax = Input::get('tax');

        SettingHelper::SaveNewCityTax($tax);

        return "done";

    }

    public function SaveInventoryManager()
    {
        $inventorymanagerid = Input::get('inventorymanagerid');

        SettingHelper::SetInventoryManager($inventorymanagerid);

        return "done";

    }

    public function ValidateProductInput($data)
    {

        $rules = array(
            'sku' => 'required|unique:productlibrary,SKU,' . $data['id'],
            'productname' => 'required|unique:productlibrary,productname,' . $data['id'],
            'charge' => 'required|numeric',
            'cost' => 'required|numeric',
            'taxable' => 'required|boolean',
            'billingfrequency' => 'required|in:none,daily,weekly,monthly,yearly',
            'stock' => 'integer',
            'reorderlevel' => 'integer',
            'restockto' => 'integer',
            'trackstock' => 'in:1,0,2',
            'companyuse' => 'in:1,0',
            'vendorid' => 'exists:vendors,id'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;

    }

    public function IncrementStock(){

        $product = ProductLibrary::find(Input::get('id'));

        if(count($product) === 1){
            $product->stock =  $product->stock + 1;
            $product->save();

            return ['status' => 'OK', 'id' => $product->id, 'stock' => $product->stock];
        }else{
            return ['status' => 'notfound'];
        }
    }

    public function DecrementStock(){

        $product = ProductLibrary::find(Input::get('id'));

        if(count($product) === 1){
            $product->stock = $product->stock - 1;
            $product->save();

            return ['status' => 'OK', 'id' => $product->id, 'stock' => $product->stock];
        }else{
            return ['status' => 'notfound'];
        }
    }



}