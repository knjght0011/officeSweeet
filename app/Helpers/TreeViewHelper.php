<?php
namespace App\Helpers;

use Schema;
use App\Models\CustomTables;

class TreeViewHelper
{   
   
    private static function DestroyArrayValues($array, $values){
        foreach($values as $value){
            $key = array_search($value, $array);
            unset($array[$key]);
        }
        return $array;
    }
   
    private static function buildTabArray($type)
    {
        $tables = CustomTables::where('type', $type)->get();
        
        $array = array();
        foreach ($tables as $table){
            $cols =  array();
            if(isset($table->fields['col1'])){
                foreach($table->fields['col1'] as $key => $value){
                    $cols[] = $key;
                }
            }
            if(isset($table->fields['col2'])){
                foreach($table->fields['col2'] as $key => $value){
                    $cols[] = $key;
                }
            }
            if(isset($table->fields['col3'])){
                foreach($table->fields['col3'] as $key => $value){
                    $cols[] = $key;
                }
            }

            $array[$table->name] = $cols;
        }
        
       return $array;
    }
    
    public static function ClientTree()
    {
        $tabs = self::buildTabArray('client');
        
        //$client = self::DestroyArrayValues(Schema::getColumnListing('clients'), ['id','notes','address_id', 'deleted_at', 'created_at', 'updated_at', 'phonenumber', 'email', 'primarycontact_id']);
        $client = array(
            "Name" => "name",
            "Category" => "category",
            "Date of Introduction" => "date_of_introduction",
            "Current Solution" => "current_solution",
            "Budget" => "budget",
            "Decision Maker" => "decision_maker",
            "Referral Source" => "referral_source",
            "Assigned To" => "assigned_to",
            "Problem Pain" => "problem_pain",
            "Resistance to Change" => "resistance_to_change",
            "Priorities" => "priorities",
            "Comments" => "comments",
            "Custom Field Text" => "custom_field_text",
            "Follow up Date" => "follow_up_date",
            "Existing Client" => "existingclient",
        );
        
        //$address = self::DestroyArrayValues(Schema::getColumnListing('address'), ['id' , 'address_id' , 'deleted_at' , 'created_at' , 'updated_at']);
        $address = array(
            "Number" => "number",
            "Address 1" => "address1",
            "Address 2" => "address2",
            "City" => "city",
            "State" => "state",
            "Region" => "region",
            "Zip" => "zip",
        );
        
        //$contacts = self::DestroyArrayValues(Schema::getColumnListing('clientcontacts'), ['id', 'client_id' , 'ref1' , 'ref2' , 'ref3' , 'address_id' , 'deleted_at' , 'created_at' , 'updated_at' , 'primaryphonenumber']);
        $contacts = array(
            "First Name" => "firstname",
            "Middle Name" => "middlename",
            "Last Name" => "lastname",
            "SSN" => "ssn",
            "Drivers License" => "driverslicense",
            "E-Mail" => "email",
            "Contact Type" => "contacttype",
            "Office Number" => "officenumber",
            "Mobile Number" => "mobilenumber",
            "Home Number" => "homenumber",
        );

        
        $clienttree =   "var clienttree = [
                        {
                        text: \"\Client Demographics\",
                        href: \"\",
                        state: {
                            expanded: false,
                        },
                        nodes: [";
        foreach($client as $key => $value){

            $clienttree =   $clienttree."{
                text: \"".$key."\",
                href: \"\Link::Client('".$value."')\",
                },";

        }
        
        $clienttree =   $clienttree."   
                        {
                        text: \"primary contact\",
                        href: \"\",
                        nodes: [";
        foreach($contacts as $key => $value)
        {
        $clienttree =   $clienttree."{
                        text: \"".$key."\",
                        href: \"\Link::ClientPrimaryContact('".$value."')\",
                        },";
        }
        
        $clienttree =   $clienttree."]
                        },
                        {
                        text: \"address\",
                        href: \"\",
                        nodes: [";
        
        foreach($address as $key => $value)
        {
        $clienttree =   $clienttree."{
                        text: \"".$key."\",
                        href: \"\Link::ClientAddress('".$value."')\",
                        },";
        }
            
        $clienttree =   $clienttree."]
                        },    
                        {
                        text: \"contacts\",
                        href: \"\",
                        nodes: [";
        foreach($contacts as $key => $value)
        {
        $clienttree =   $clienttree."{
                        text: \"".$key."\",
                        href: \"\Link::ClientContact('".$value."')\",
                        },";
        }
            
        $clienttree =   $clienttree."]},"; #;";
        
        $clienttree =  $clienttree. "{
                text: \"\Tab Data\",
                href: \"\",
                nodes: [";
        
        foreach($tabs as $key => $value)
        {
            $clienttree =   $clienttree."{
                        text: \"".$key."\",
                        nodes: [";
                        foreach($value as $key1 => $value1){
                            $clienttree =   $clienttree."{
                                text: \"".$value1."\",
                                href: \"\Link::ClientTabData('".$key."','".$value1."')\",
                                },";
                        }
            $clienttree =   $clienttree."]},";
        }
        
        $clienttree =   $clienttree."]}]}];";
        
        
        return $clienttree;
    }
    
    public static function VendorTree()
    {
        $tabs = self::buildTabArray('vendor');
        
        //$vendor = self::DestroyArrayValues(Schema::getColumnListing('vendors'), ['id','notes','address_id', 'deleted_at', 'created_at', 'updated_at', 'phonenumber', 'email', 'primarycontact_id']);
        $vendor = array(
            "Name" => "name",
            "Category" => "category",
            "Track 1099" => "track_1099",
            "Tax ID Number" => "tax_id_number",
        );

        //$address = self::DestroyArrayValues(Schema::getColumnListing('address'), ['id' , 'address_id' , 'deleted_at' , 'created_at' , 'updated_at']);
        $address = array(
            "Number" => "number",
            "Address 1" => "address1",
            "Address 2" => "address2",
            "City" => "city",
            "State" => "state",
            "Region" => "region",
            "Zip" => "zip",
        );

        //$contacts = self::DestroyArrayValues(Schema::getColumnListing('clientcontacts'), ['id', 'client_id' , 'ref1' , 'ref2' , 'ref3' , 'address_id' , 'deleted_at' , 'created_at' , 'updated_at' , 'primaryphonenumber']);
        $contacts = array(
            "First Name" => "firstname",
            "Middle Name" => "middlename",
            "Last Name" => "lastname",
            "SSN" => "ssn",
            "Drivers License" => "driverslicense",
            "E-Mail" => "email",
            "Contact Type" => "contacttype",
            "Office Number" => "officenumber",
            "Mobile Number" => "mobilenumber",
            "Home Number" => "homenumber",
        );

        $vendortree =   "var vendortree = [
                        {
                        text: \"\Vendor Demographics\",
                        href: \"\",
                        state: {
                            expanded: false,
                        },
                        nodes: [";
        foreach($vendor as $key => $value)
        {
                $vendortree =   $vendortree."{
                    text: \"".$key."\",
                    href: \"\Link::Vendor('".$value."')\",
                    },";

        }
        
        $vendortree =   $vendortree."   {
                        text: \"primary contact\",
                        href: \"\",
                        nodes: [";
        foreach($contacts as $key => $value)
        {
        $vendortree =   $vendortree."{
                        text: \"".$key."\",
                        href: \"\Link::VendorPrimaryContact('".$value."')\",
                        },";
        }
        
        $vendortree =   $vendortree."]},{
                        text: \"address\",
                        href: \"\",
                        nodes: [";
        
        foreach($address as $key => $value)
        {
            if($value === "number"){
                $vendortree =   $vendortree."{
                                text: \"house name or number\",
                                href: \"\Link::VendorAddress('".$value."')\",
                                },";                
            }else{
                $vendortree =   $vendortree."{
                                text: \"".$key."\",
                                href: \"\Link::VendorAddress('".$value."')\",
                                },";                
            }
        }
            
        $vendortree =   $vendortree."]
                        },    
                        {
                        text: \"contacts\",
                        href: \"\",
                        nodes: [";
        foreach($contacts as $key => $value)
        {
        $vendortree =   $vendortree."{
                        text: \"".$key."\",
                        href: \"\Link::VendorContact('".$value."')\",
                        },";
        }
        
        $vendortree =  $vendortree. "]},{
                text: \"\Tab Data\",
                href: \"\",
                nodes: [";
        
        foreach($tabs as $key => $value)
        {
            $vendortree =   $vendortree."{
                        text: \"".$key."\",
                        nodes: [";
                        foreach($value as $key1 => $value1){
                            $vendortree =   $vendortree."{
                                text: \"".$value1."\",
                                href: \"\Link::VendorTabData('".$key."','".$value1."')\",
                                },";
                        }
            $vendortree =   $vendortree."]},";
        }
        
        $vendortree =   $vendortree."]}]}];";
        
        return $vendortree;
    }
    
    public static function EmployeeTree()
    {        
        //$employees = self::DestroyArrayValues(Schema::getColumnListing('employees'), ['id', 'address_id' , 'deleted_at' , 'created_at' , 'updated_at']);
        $employees = array(
            "Employee ID" => "employeeid",
            "First Name" => "firstname",
            "Middle Name" => "middlename",
            "Last Name" => "lastname",
            "SSN" => "ssn",
            "Drivers License" => "driverslicense",
            "E-Mail" => "email",
            "department" => "department",
            "Office Number" => "officenumber",
            "Mobile Number" => "mobilenumber",
            "Home Number" => "homenumber",
        );

        //$address = self::DestroyArrayValues(Schema::getColumnListing('address'), ['id' , 'address_id' , 'deleted_at' , 'created_at' , 'updated_at']);
        $address = array(
            "Number" => "number",
            "Address 1" => "address1",
            "Address 2" => "address2",
            "City" => "city",
            "State" => "state",
            "Region" => "region",
            "Zip" => "zip",
        );

        $employeetree =   "var employeetree = [
                        {
                        text: \"\Employee Demographics\",
                        href: \"\",
                        state: {
                            expanded: false,
                        },
                        nodes: [";
        foreach($employees as $key => $value)
        {
        $employeetree =   $employeetree."{
                        text: \"".$key."\",
                        href: \"\Link::Employee('".$value."')\",
                        },";
        }
        
        $employeetree =   $employeetree."{
                        text: \"address\",
                        href: \"\",
                        nodes: [";
        
        foreach($address as $key => $value)
        {
        $employeetree =   $employeetree."{
                        text: \"".$key."\",
                        href: \"\Link::EmployeeAddress('".$value."')\",
                        },";
        }
            
        $employeetree =   $employeetree."   ]   }   ]   }   ];";
        
        return $employeetree;
    }
    
    public static function GeneralTree()
    {
        
        $generaltree = "var generaltree = [
                    {
                      text: \"General Info\",
                      state: {
                            expanded: false,
                        },
                      nodes: [
                        {
                          text: \"Current Date in Words (Example: 17th January 2017)\",
                          href: \"\Link::General('currentdatewords')\",
                        },
                        {
                          text: \"Current Date:ISO (YYYY\\\MM\\\DD)\",
                          href: \"\Link::General('currentdateiso')\",
                        },
                        {
                          text: \"Current Date:American (MM\\\DD\\\YYYY)\",
                          href: \"\Link::General('currentdateamerican')\",
                        },
                        {
                          text: \"Current Date:European (DD\\\MM\\\YYYY)\",
                          href: \"\Link::General('currentdateeuropean')\",
                        },
                        {
                          text: \"Current Time:24 Hours\",
                          href: \"\Link::General('currenttime24')\",
                        },
                        {
                          text: \"Current Time:12 Hours\",
                          href: \"\Link::General('currenttime12')\",
                        }]}];";
         
        
        return $generaltree;
    }
    
    public static function CompanyInfoTree()
    {
         $tree = "var companytree = [
                    {
                        text: \"Our Company Info\",
                        state: {
                            expanded: false,
                        },
                        nodes: [
                            {
                              text: \"Company Name\",
                              href: \"\Link::CompanyInfo('name')\",
                            },
                            {
                              text: \"Company Email\",
                              href: \"\Link::CompanyInfo('email')\",
                            },
                            {
                              text: \"OfficeSweeet URL\",
                              href: \"\Link::CompanyInfo('osurl')\",
                            },
                            {
                                text: \"Company Address\",
                                nodes: [
                                    {
                                        text: \"House Name or Number\",
                                        href: \"\Link::CompanyAddress('number')\",
                                    },
                                    {
                                        text: \"Street\",
                                        href: \"\Link::CompanyAddress('address1')\",
                                    },
                                    {
                                        text: \"Address Line 2\",
                                        href: \"\Link::CompanyAddress('address2')\",
                                    },
                                    {
                                        text: \"City\",
                                        href: \"\Link::CompanyAddress('city')\",
                                    },
                                    {
                                        text: \"State\",
                                        href: \"\Link::CompanyAddress('state')\",
                                    },
                                    {
                                        text: \"Zip\",
                                        href: \"\Link::CompanyAddress('zip')\",
                                    },     
                                    {
                                        text: \"Phone Number\",
                                        href: \"\Link::CompanyAddress('phonenumber')\",
                                    },  
                                    {
                                        text: \"Fax Number\",
                                        href: \"\Link::CompanyAddress('faxnumber')\",
                                    }                                 
                                ]
                            }
                        ]
                    }
                ];";
         
        return $tree;
    }
}