<?php

namespace App\Models\OS\Financial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Helpers\FormatingHelper;

use App\Models\MonthEnd;

class Asset extends Model
{
    use SoftDeletes;

    protected $table = 'assets';

    protected $dates = ['date'];

    protected $casts = [
        'catagorys' => 'array',
        'accounts' => 'array',
    ];

    public function depreciation()
    {
        return $this->hasMany('App\Models\OS\Financial\Depreciation', 'asset_id', 'id');
    }

    public function filestore()
    {
        return $this->belongsTo('App\Models\OS\FileStore', 'filestore_id', 'id');
    }

    public function getAmount()
    {
        return number_format($this->amount , 2, '.', '');
    }

    public function formatedAmount()
    {
        return "$" . number_format($this->amount , 2, '.', '');
    }

    public function DateString()
    {
        return $this->date->toDateString();
    }

    public function formatDateISO()
    {
        return FormatingHelper::DateISO($this->date);
    }

    public function getFile(){
        if($this->filestore_id === null){
            return "";
        }else{
            return $this->filestore->file;
        }
    }

    public function getCatagorys(){
        if($this->catagorys === null){
            return ['Unknown' => $this->amount];
        }else{
            return $this->catagorys;
        }
    }

    public function catagoryJson()
    {
        $array = array();
        foreach($this->getCatagorys() as $key => $value){
            $array[$key] = $value;
        }
        return json_encode($array);
    }

    public function getAccounts(){
        if($this->accounts === null){
            return ['Unknown' => $this->amount];
        }else{
            return $this->accounts;
        }
    }

    public function accountJson()
    {
        $array = array();
        foreach($this->getAccounts() as $key => $value){
            $array[$key] = $value;
        }
        return json_encode($array);
    }


    public function TypeString(){

        switch ($this->type) {
            case "a":
                return "Asset";
            case "l":
                return "Liability";
            case "e":
                return "Equity";
        }
    }

    public function DataArray(){
        $array = array(
            'expand' => '<span id="expandindicator" class="glyphicon glyphicon-minus" aria-hidden="true"></span>',
            'name' => $this->name,
            'date' => $this->formatDateISO(),
            'amount' => $this->amount,
            'type' => $this->TypeString(),
            'file' => "",
            'id' => $this->id,
            'catagorys' => $this->catagoryJson(),
            'comments' => $this->comments,
            'file_id' => $this->filestore_id,
            'valid' => "true",
            'journal-tracked' => "",
            'journal-can-toggle' => "",
            'depreciation' => $this->DepreciationJSON(),
            'accounts' => $this->accountJson()
            );

        if($this->type = "a"){
            $array['amountlessdepreciation'] = $this->AmountLessDepreciation();
        }else{
            $array['amountlessdepreciation'] = "";
        }

        if($this->journal === 1){
            $array['journal-tracked'] = "checked";
        }else{
            $array['journal-tracked'] = "";
        }

        if($this->CantEdit()){
            $array['journal-can-toggle'] = "disabled";
        }else{
            $array['journal-can-toggle'] = "";
        }

        if($this->filestore_id != null){
            $array['file'] = '<button style="width: 100%; padding-top: 2px; padding-bottom: 2px;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model"data-fileid="'. $this->filestore_id .'">Show Attachment</button>';
        }

        return $array;
    }

    public function CantEdit()
    {
        $lastmonthend = MonthEnd::get()->last();
        if(count($lastmonthend) === 1){
            return $lastmonthend->IsBeforeThis($this->date);
        }else{
            return false;
        }
    }

    public function dateforinput()
    {
        return $this->date->format('Y-m-d');
    }

    public function DepreciationJSON(){

        if(count($this->depreciation) > 0){
            $array = array();
            foreach ($this->depreciation as $depreciation){

                $array2 = array(
                    'date' => $depreciation->date->toDateString(),
                    'amount' => number_format($depreciation->amount, 2, '.', ''),
                    'id' => $depreciation->id
                );

                array_push($array, $array2);
            }

            $json = json_encode($array);
            return $json;
        }else{
            return "none";
        }

    }

    public function AmountLessDepreciation(){

        $total = 0.00;
        foreach ($this->depreciation as $depreciation){
            $total = $total + $depreciation->amount;
        }

        return $this->amount - $total;

    }
}
