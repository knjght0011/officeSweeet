<?php

namespace App\Http\Controllers\OS\Financial;

#use Session;
use App\Helpers\OS\Financial\JournalHelper;
use App\Http\Controllers\Controller;
use App\Models\OS\Financial\Depreciation;
use App\Models\OS\Inventory\ServiceLibrary;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use App\Models\OS\Financial\Asset;
use App\Models\ProductLibrary;

use App\Helpers\OS\SettingHelper;


class AssetController extends Controller {
    
    public function showOverview()
    {
        $assets = Asset::all();
        $endingbalance = JournalHelper::CurrentBalance();
        $journalcatagorys = SettingHelper::GetSetting('journal-catagorys');
        $inventorycatagorys = SettingHelper::GetSetting('inventory-catagorys');

        $vendors = Vendor::all();

        $products = ProductLibrary::all();
        $services = ServiceLibrary::all();

        $inventorytotal  = 0;
        foreach($products as $product){
            if($product->companyuse === 0 & $product->trackstock === 1){
                $inventorytotal = $inventorytotal + $product->CurrentStockValue();
            }
        }

        return View::make('OS.Assets.main')
            ->with('journalcatagorys', $journalcatagorys)
            ->with('inventorycatagorys', $inventorycatagorys)
            ->with('endingbalance', $endingbalance)
            ->with('assets', $assets)
            ->with('inventorytotal', $inventorytotal)
            ->with('products', $products)
            ->with('services', $services)
            ->with('vendors', $vendors);

    }

    public function showAsset($subdomain, $id)
    {
        $asset = Asset::where('id', '=', $id)->first();

        if(count($asset) === 1){
            return View::make('OS.Assets.add')

                ->with('asset', $asset);
        }else{
            return View::make('OS.Assets.add');
        }
    }

    public function saveDepreciation(){

        $asset = Asset::where('id', Input::get('assetid'))->first();

        if(count($asset) === 1){

            $data = array(
                'id' => Input::get('id'),
                'date' => Carbon::parse(Input::get('date')),
                'amount' => Input::get('amount'),
            );

            $depreciation = Depreciation::where('id', $data['id'])->first();

            if(count($depreciation) != 1){
                $depreciation = new Depreciation();
            }

            $depreciation->date = $data['date'];
            $depreciation->amount = $data['amount'];
            $depreciation->asset_id = $asset->id;
            $depreciation->save();

            return ['status' => 'OK', 'id' => $depreciation->id, 'date' => $depreciation->date->toDateString(), 'amount' => number_format($depreciation->amount, 2, '.', ''), 'json' => $asset->DepreciationJSON(), 'assetlessdepriciation' => number_format($asset-> AmountLessDepreciation(), 2, '.', '')];
        }else{
            return ['status' => 'notfound'];
        }
    }

    public function DeleteDepreciation(){

        $depreciation = Depreciation::where('id', Input::get('id'))->first();

        if(count($depreciation) === 1){

            $assetid = $depreciation->asset_id;
            $depreciation->delete();

            $asset = Asset::where('id', $assetid)->first();

            $return = array(
                'status' => 'OK' ,
                'date' => count($asset->depreciation) . ". " . $depreciation->date->toDateString(),
                'amount' => number_format($depreciation->amount, 2, '.', ''),
                'json' => $asset->DepreciationJSON(),
                'assetlessdepriciation' => number_format($asset->AmountLessDepreciation(), 2, '.', '')
            );

            return $return;
        }else{
            return ['status' => 'notfound'];
        }

    }


    public function saveAsset()
    {
        $datesent = Input::get('date');

        $data = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'date' => Carbon::parse($datesent),
            'comments' => Input::get('comments'),
            'amount' => Input::get('amount'),
            'catagorys' => Input::get('catagorys'),
            'accounts' => Input::get('accounts'),
            'type' => Input::get('type'),
            'file_id' => Input::get('file_id'),
            'journal' => Input::get('journal'),
        );




        if($data['id'] === "journal" or $data['id'] === "inventory"){

            $data['catagorys'] = json_encode($data['catagorys']);

            if (is_array($data['accounts'])) {
                $data['accounts'] = json_encode($data['accounts']);
            }

            SettingHelper::SetSetting($data['id'] . '-catagorys', $data['catagorys']);

            $data['expand'] = '<span id="expandindicator" class="glyphicon glyphicon-minus" aria-hidden="true"></span>';
            $data['file'] = "";

            if($data['type'] === "a"){
                $data['type'] = "Asset";
            }else{
                $data['type'] = "Liability";
            }

            $data['valid'] = "true";

            $data['amount'] = number_format($data['amount'], 2);

            return $data;
        }

        $asset = Asset::where('id', '=', $data['id'])->first();

        if(count($asset) === 1){
            $valid = $this->ValidateAssetInput($data, false);
        }else{
            $asset =  new Asset;
            $valid = $this->ValidateAssetInput($data, true);
        }

        if ($valid->fails()){
            return ['status' => 'validation', 'errors' => $valid->errors()->toArray()];
        }else{

            $asset->name = $data['name'];
            $asset->date = $data['date'];
            $asset->comments = $data['comments'];
            $asset->amount = $data['amount'];

            if(is_array($data['catagorys'])){
                $asset->catagorys = $data['catagorys'];
            }else{
                $asset->catagorys = null;
            }

            if(is_array($data['accounts'])){
                $asset->accounts = $data['accounts'];
            }else{
                $asset->accounts = null;
            }

            //$asset->catagorys = $data['catagorys'];
            $asset->type = $data['type'];
            if($data['file_id'] === ""){
                $asset->filestore_id = null;
            }else{
                $asset->filestore_id = $data['file_id'];
            }

            if($data['journal'] === "1"){
                if($asset->CantEdit() === true){
                    $error["error"] = "Cannot create with given date as a month end has allready been actioned after that date";
                    return ['status' => 'monthend'];
                }else{
                    $asset->journal = 1;
                }
            }

            $asset->save();

            return ['status' => 'OK', 'data' => $asset->DataArray(), 'datesent' => $datesent, 'dateparsed' => $data['date'], 'dateasset' => $asset->date];
        }
    }

    public function deleteAsset(){

        $asset = Asset::where('id', Input::get('id'))->first();

        if(count($asset) === 1){
            if($asset->journal === 1){
                return ['status' => 'injournal'];
            }else{

                foreach ($asset->depreciation as $depreciation){
                    $depreciation->delete();
                }

                $asset->delete();

                return ['status' => 'OK'];
            }
        }else{
            return ['status' => 'notfound'];
        }

    }

    public static function ValidateAssetInput($data, $new)
    {
        $rules = array(
            'name' => 'string',
            'date' => 'date',
            'comments' => 'string',
            'amount' => 'numeric',
            'catagorys' => 'array',
            'accounts|null' => 'array',
            'type' => 'in:a,l,e',
        );

        if(!$new) {
            $rules['id'] = 'exists:assets,id';
        }

        if(array_key_exists('file_id', $data)) {
            if($data['file_id'] != ""){
                $rules['file_id'] = 'exists:filestore,id';
            }
        }

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;
    }

    public function ToggleJournal(){

        $asset = Asset::where('id', '=', Input::get('id'))->first();

        if(count($asset) === 1){
            if($asset->CantEdit()){
                return ['status' => 'cantedit'];
            }else{

                $asset->journal = Input::get('journal');
                $asset->save();

                return ['status' => 'OK'];
            }
        }else{
            return ['status' => 'notfound'];
        }

    }
}
