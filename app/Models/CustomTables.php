<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

use App\Helpers\OS\CustomTabHelper;

use App\Models\CustomTabData;

class CustomTables extends CustomBaseModel  {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customtables';

    protected $casts = [
        'fields' => 'array',
    ];

	protected function TableName(){

        $name = "customtable_";
        $name .= $this->type;
        $name .= "_";
        $name .= $this->name;

        return $name;
    }

    protected function ForeignKey(){
        return $key = $this->type . "_id";
    }

	public function Data($dataid){

        switch ($this->type) {
            case "client":
                $tabdata = CustomTabData::where('customtables_id', '=', $this->id)->where('client_id', '=', $dataid)->first();
                break;
            case "vendor":
                $tabdata = CustomTabData::where('customtables_id', '=', $this->id)->where('vendor_id', '=', $dataid)->first();
                break;
            default:

        }
        if(count($tabdata) === 1){

            return $tabdata->data;

        }else{

            return array();
        }

    }

    public function RecordExists($id)
    {
        $results = DB::table($this->TableName())->select('*')->where($this->ForeignKey(), $id)->count();
        if ($results === 0)
        {
            return false;
        }else{
            return true;
        }
    }

    public function SaveData($id, $object){

        if($this->RecordExists($id))
        {
            DB::table($this->TableName())
                ->where($this->ForeignKey(), $id)
                ->update($object);
        }else{
            $object[$this->ForeignKey()] = $id;
            DB::table($this->TableName())
                ->insert($object);
        }
    }

    public function HTML(){
        return CustomTabHelper::GenerateHTML($this->fields);
    }
}