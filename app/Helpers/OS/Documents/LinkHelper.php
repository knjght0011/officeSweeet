<?php
namespace App\Helpers\OS\Documents;

use Illuminate\Support\Facades\Auth;

use App\Models\Setting;
use App\Models\CustomTables;
use App\Models\Branch;


class LinkHelper
{

    public static function CompanyInfo($name)
    {
        try{
            switch ($name) {
                case 'name':
                    $cname = Setting::where('name', 'companyname')->first();
                    if(count($cname) == 1){
                        return $cname->value;
                    }else{
                        return "<b>No Company Name Set</b>";
                    }
                case 'email':
                    $cemail = Setting::where('name', 'companyemail')->first();
                    if(count($cemail) == 1){
                        return $cemail->value;
                    }else{
                        return "<b>No Company Email Set</b>";
                    }
                case 'osurl':
                    return app()->make('account')->subdomain . ".officesweeet.com";
                default:
                    return "<b>ERROR:Unknown Key</b>";
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function CompanyAddress($name)
    {
        try{
            if(Auth::user()->branch_id === null){
                $branch = Branch::where('default', 1)->first();
                if(count($branch) === 1){
                    if($branch->$name === null){
                        return "<b>ERROR:Unknown Key</b>";
                    }else{
                        return $branch->$name;
                    }
                }else{
                    return "<b>ERROR:Unable to find a branch</b>";
                }
            }else{
                $branch = Branch::where('id', Auth::user()->branch_id)->first();
                if($branch->$name === null){
                    return "<b>ERROR:Unknown Key</b>";
                }else{
                    return $branch->$name;
                }
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function General($name)
    {
        try{
            switch ($name) {
                case 'currentdatewords':
                    return date("jS F Y");
                case 'currentdateiso':
                    return date("Y/m/d");
                case 'currentdateamerican':
                    return date("m/d/Y");
                case 'currentdateeuropean':
                    return date("d/m/Y");
                case 'currenttime24':
                    return date("G:i");
                case 'currenttime12':
                    return date("g:i A");
                default:
                    return "<b>ERROR:Unknown Key</b>";
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function Client($name)
    {
        try{
            $client = app()->make('Template-Client');
            if($name === "name"){
                return $client->getName();
            }else{
                if($client->$name === null){
                    return "<b>ERROR:Unknown Key</b>";
                }else{
                    return $client->$name;
                }
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function ClientPrimaryContact($name)
    {
        try{
            $client = app()->make('Template-Client');
            if($client->primarycontact->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $client->primarycontact->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function ClientAddress($name)
    {
        try{
            $client = app()->make('Template-Client');
            if($client->address->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $client->address->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function ClientContact($name, $number = 0)
    {
        try{
            $client = app()->make('Template-Client');

            if($client->contacts[$number]->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $client->contacts[$number]->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function ClientTabData($table, $key)
    {
        try{
            $client = app()->make('Template-Client');
            $tabledata = CustomTables::where('name', $table)->first();
            if(count($tabledata) === 1){
                $data = $tabledata->Data($client->id);
                if (!isset($data[$key])) {
                    return "<b>ERROR:No Data Found</b>";
                } else {
                    return $data[$key];
                }
            }else{
                return "<b>ERROR:Unknown Table</b>";
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function Vendor($name)
    {
        try{
            $vendor = app()->make('Template-Vendor');
            if($name === "name"){
                return $vendor->getName();
            }else {
                if ($vendor->$name === null) {
                    return "<b>ERROR:Unknown Key</b>";
                } else {
                    return $vendor->$name;
                }
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function VendorPrimaryContact($name)
    {
        try{
            $vendor = app()->make('Template-Vendor');
            if($vendor->primarycontact->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $vendor->primarycontact->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function VendorAddress($name)
    {
        try{
            $vendor = app()->make('Template-Vendor');
            if($vendor->address->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $vendor->address->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function VendorContact($name, $number = 0)
    {
        try{
            $vendor = app()->make('Template-Vendor');
            if($vendor->contacts[$number]->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $vendor->contacts[$number]->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function VendorTabData($table, $key)
    {
        try{
            $vendor = app()->make('Template-Vendor');
            $tabledata = CustomTables::where('name', $table)->first();
            if(count($tabledata) === 1){
                $data = $tabledata->Data($vendor->id);
                if (!isset($data->$key)) {
                    return "<b>ERROR:Unknown Key</b>";
                } else {
                    return $data->$key;
                }
            }else{
                return "<b>ERROR:Unknown Table</b>";
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function Employee($name)
    {
        try{
            $employee = app()->make('Template-Employee');
            if($employee->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $employee->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }

    public static function EmployeeAddress($name)
    {
        try{
            $employee = app()->make('Template-Employee');
            if($employee->address->$name === null){
                return "<b>ERROR:Unknown Key</b>";
            }else{
                return $employee->address->$name;
            }
        }catch (\Throwable $t) {
            return "<b>ERROR:Something Went Wrong</b>";
        }

    }
}