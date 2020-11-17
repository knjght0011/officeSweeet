<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
#use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
#use Session;
use Illuminate\Validation\Rule;

use \App\Providers\EventLog;

use App\Models\CustomTables;
use App\Models\CustomTabData;

class CustomTablesController extends Controller {

    ###tab functions
    public function BuildTab($subdomain, $tabid, $dataid)
    {
        $table =  CustomTables::where('id', $tabid)->first();
        if(count($table) === 1){

            return View::make('OS.CustomTable.tab')
                ->with('table', $table)
                ->with('dataid', $dataid);
        }else{
            return "unknown tab";
        }
    }

    //Saveing Tab Data
    public function SaveSingleTab()
    {
        $data = array(
            'tableid' => Input::get('tableid'),
            'dataid' => Input::get('dataid'),
            'tabdata' => Input::get('tabdata'),
        );

        $tab = CustomTables::where('id', '=', $data['tableid'])->withTrashed()->first();

        switch ($tab->type) {
            case "client":
                $tabdata = CustomTabData::where('customtables_id', '=', $tab->id)->where('client_id', '=', $data['dataid'])->first();
                $clientid = $data['dataid'];
                $vendorid = null;
                break;
            case "vendor":
                $tabdata = CustomTabData::where('customtables_id', '=', $tab->id)->where('vendor_id', '=', $data['dataid'])->first();
                $clientid = null;
                $vendorid = $data['dataid'];
                break;
            default:

        }

        if(count($tabdata) === 1){

            $tabdata->data = $data['tabdata'];
            $tabdata->save();
            return "ok";

        }else{

            $tabdata = new CustomTabData;
            $tabdata->customtables_id = $tab->id;
            $tabdata->client_id = $clientid;
            $tabdata->vendor_id = $vendorid;
            $tabdata->data = $data['tabdata'];
            $tabdata->save();
            return "ok";
        }
    }
    

   
    ###acp functions
    public function GetID($subdomain, $id)
    {
        $tables = CustomTables::where('id', '=', $id)->withTrashed()->first();

        if(count($tables) === 1){
            $data = array(
                'type' => $tables->type,
                'html' => $tables->HTML(),
            );

            if($tables->deleted_at === null){
                $data['enabled'] = 1;
            }else{
                $data['enabled'] = 0;
            }

            return $data;
        }else{
            return "table not found";
        }

    }

    public function SaveTable()
    {
        $data = array(
            'id' => intval(Input::get('id')),
            'name' => $this->SanitiseName(Input::get('name')),
            'displayname' => Input::get('name'),
            'fields' => Input::get('fields'),
            'type' => Input::get('type'),
        );

        if($data['id'] === 0){
            $tab = new CustomTables;
        }else{
            $tab = CustomTables::where('id', '=', $data['id'])->withTrashed()->first();
        }

        $tab->name = $data['name'];
        $tab->displayname = $data['displayname'];
        $tab->content = "";
        $tab->type = $data['type'];
        $tab->fields = $data['fields'];
        $tab->save();

        return $tab->id;
    }



    public function SaveTable12()
    {        
        $data = array(
            'id' => intval(Input::get('id')),
            'name' => $this->SanitiseName(Input::get('name')), 
            'displayname' => Input::get('name'), 
            'html' => Input::get('html'),
            'type' => Input::get('type'),
        );
        
        
        #return var_dump($data);
        $fields = $this->GetFieldNames($data['html']);
        
        if ($this->HasDupes($fields)){
            return "There are atleast 2 fields with the same name.";
        }else{
            #we have the list and there is no dupes, do the thing
            if($data['id'] === 0){
                            
                $rules = array(
                    'name' => 'unique:customtables,name', 
                    'type' => 'in:client,vendor',
                );

                $validator = Validator::make($data, $rules);

                if ($validator->fails()){

                    return $validator->errors()->toArray();

                } else {


                    $sql = $this->CreateTableSQL($data['name'], $fields, $data['type']);
                    DB::statement($sql);
                    
                    $id = $this->UpdateIndexTable($data);
                    
                    return $id;

                    #return var_dump($sql);
                }    
            }else{
                $existing = CustomTables::find($data['id']);
                $oldfields = $this->GetFieldNames($existing->content); #$this->GetTableColumnNames($existing->name, $data['type']);
                $delete = array_diff($oldfields, $fields);
                $add = array_diff($fields, $oldfields);
                if (count($add) > 0){
                    $sql = $this->AddColumnSQL($existing->name, $add, $existing->type);
                    foreach($sql as $statement){
                        DB::statement($statement);
                    }
                }
                $id = $this->UpdateIndexTable($data);
                
                return $id;
            }
        }
    }
    
    public function SanitiseName($displayname){
        $lower = strtolower($displayname);
        $nospecial = preg_replace("/[^a-z0-9.]+/i", "", $lower);
        
        return $nospecial;
        
    }
    
    #when passed an HTML form will create anrray of field names based on elements in that form
    public function GetFieldNames(string $HTML)
    {
        $names = array();
        
        $DOM = new \DOMDocument;
        $DOM->loadXML($HTML);
        
        $items = $DOM->getElementsByTagName('input');
        
        foreach($items as $item){
            array_push($names, $item->getAttribute('name'));
        }
        
        $items = $DOM->getElementsByTagName('textarea');
        
        foreach($items as $item){
            array_push($names, $item->getAttribute('name'));
        }
        
        $items = $DOM->getElementsByTagName('select');
        
        foreach($items as $item){
            array_push($names, $item->getAttribute('name'));
        }
        
        return $names;
    }
    
    #given a tablename and type will return an array of coloumn names
    public function GetTableColumnNames(string $tablename , string $type)
    {
        $names = array();
        $statement = "DESCRIBE customtable_";
        $statement .= $type;
        $statement .= "_";
        $statement .= $tablename;
        
        $cols = DB::select($statement);
        foreach($cols as $col){
            array_push($names, $col->Field);
        }
        return $names;
    }
    
    #given an array will return true if there are duplicate entrys, flase if not
    public function HasDupes($array)
    {
        $array2 = array_unique($array);
        
        if (count($array2) === count($array)){
            return false;
        }else{
            return true;
        }
    }
    
    #generates an sql statement to make a table based on a name and an array of field names
    public function CreateTableSQL(string $name, array $fields, string $type)
    {   
        
        $statement = "CREATE TABLE ";
        $statement .= "customtable_";
        $statement .= $type;
        $statement .= "_";
        $statement .= $name;
        $statement .= " (";
        $statement .= "id int NOT NULL AUTO_INCREMENT, ";
        
        foreach($fields as $field){

            $statement .= $field;
            $statement .= ' varchar(255), ';
        }
        
        switch ($type) {
            case "client":
                $statement .= "client_id int UNSIGNED, ";
                $statement .= "PRIMARY KEY (id), ";
                $statement .= "FOREIGN KEY (client_id) REFERENCES clients(id) )";
                break;
            case "vendor":
                $statement .= "vendor_id int UNSIGNED, ";
                $statement .= "PRIMARY KEY (id), ";
                $statement .= "FOREIGN KEY (vendor_id) REFERENCES vendors(id) )";
                break;
        }
        
        return $statement;
    }
    

    
    #generates an sql statement to add cplumns to table based on a name and an array of field names
    public function AddColumnSQL(string $name, array $fields, string $type)
    {   
        #$count = count($fields);
        $n = 1;
        foreach($fields as $field)
        {
            $statement[$n] = "ALTER TABLE ";
            $statement[$n] .= "customtable_";
            $statement[$n] .= $type;
            $statement[$n] .= "_";
            $statement[$n] .= $name;
            $statement[$n] .= " ADD ";
            $statement[$n] .= $field;
            $statement[$n] .= " varchar(255);";

            $n++;
        }
        
        return $statement;
    }
    
    #updates the table Customtable: if passed id is 0 will make a new record otherwise will updated the supplyed record ID
    public function UpdateIndexTable(array $data)
    {  
        if($data['id'] === 0){
            
            $table = new CustomTables;
            $table->name = $data['name'];
            $table->displayname = $data['displayname'];
            $table->content = $data['html'];
            $table->type = $data['type'];
            $table->save();

            return $table->id;
  
        }else{
            $table = CustomTables::find($data['id']);
            //$table->displayname = $data['displayname'];
            $table->content = $data['html'];
            $table->save();
            
            return $table->id;
        }
    }
    
    public function DeactivateTable()
    {
        $table = CustomTables::where('id' , Input::get('id'))->withTrashed()->first();

        if (count($table) === 1){
            if($table->deleted_at == null){
                $table->delete();
                EventLog::add('Custom Tab disabled ID:'.$table->id.' Name:'.$table->displayname);
                return ['status' => 'OK', 'action' => 'disabled'];
            }else{
                $table->restore();
                EventLog::add('Custom Tab enabled ID:'.$table->id.' Name:'.$table->displayname);
                return ['status' => 'OK', 'action' => 'enabled'];
            }
        } else {
            return ['status' => 'notfound'];
        }  
    }
    
    public function RenameTable(){
        
        $data = array(
            'id' => Input::get('id'),
            'displayname' => Input::get('displayname'),
        );
        
        $rules = array(
            'id' => 'exists:customtables,id', 
            'displayname' => Rule::unique('customtables')->ignore($data['id']),
        );
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            
            $table = CustomTables::where('id' , $data['id'])
                    ->withTrashed()->first();
            
            $table->displayname = Input::get('displayname');
            $table->save();
            
            EventLog::add('Custom Tab renamed ID:'.$table->id.' New Name:'.$table->displayname);
            
            return $table->id;
   
        }  
    }
}