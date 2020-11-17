<?php
namespace App\Helpers\OS;

#use Illuminate\Support\Facades\Cache;
use App\Models\OS\Training\TrainingModule;
use App\Models\OS\Training\TrainingRequest;
use Illuminate\Support\Facades\Validator;

use App\Models\OS\FileStore;

class FileStoreHelper
{
    public static function ValidatefileInput($data)
    {
        $rules = array(
            #'file' => 'file',
            'description' => 'string',
        );


        if(array_key_exists('id', $data)) {
            $rules['id'] = 'exists:filestore,id';
        }

        if(array_key_exists('client_id', $data)) {
            if($data['client_id'] != null){
                $rules['client_id'] = 'exists:clients,id';
            }
        }

        if(array_key_exists('vendor_id', $data)) {
            if($data['vendor_id'] != null) {
                $rules['vendor_id'] = 'exists:vendors,id';
            }
        }

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;
    }

    public static function StoreFileAndReturn($data){

        $file = new FileStore;
        $file->file = $data['file'];
        $file->description = $data['description'];

        if(isset($data['client_id'])){
            $file->client_id = $data['client_id'];
        }else{
            $file->client_id = null;
        }

        if(isset($data['vendor_id'])){
            $file->vendor_id = $data['vendor_id'];
        }else{
            $file->vendor_id = null;
        }

        if(isset($data['user_id'])){
            $file->user_id = $data['user_id'];
        }else{
            $file->user_id = null;
        }

        $file->upload_user = $data['upload_user'];
        $file->save();

        if(isset($data['updatetype'])){
            if($data['updatetype'] != null){
                switch ($data['updatetype']) {
                    case "companylogo":
                        SettingHelper::SetSetting('companylogo', $file->id);
                        break;
                    case "trainingrequest":
                        $TrainingRequest = TrainingRequest::where('id', $data['updateid'])->first();
                        if(count($TrainingRequest) === 1){
                            $TrainingRequest->filestore_id = $file->id;
                            $TrainingRequest->save();
                        }
                        break;
                    case "":

                        break;
                }
            }
        }

        return $file;
    }

    public static function StoreFile($data){

        $file = self::StoreFileAndReturn($data);

        return $file->id;
    }


    public static function FileDescription($id, $description){

        $file = FileStore::where('id' , '=' , $id)->first();
        $file->description = $description;
        $file->save();

        return $file->id;
    }

    public static function FileDelete($id){

        $file = FileStore::where('id' , '=' , $id)->first();
        if(count($file)){
            $file->delete();
            return true;
        }else{
            return false;
        }
    }

    public static function getPrefix($file){
        $split = explode ( "," , $file);
        return $split[0];
    }

    public static function getSuffix($file){
        $split = explode ( "," , $file);
        return $split[1];
    }

    public static function getMime($file){
        $type1 = str_replace ( "data:","" , self::getPrefix($file));
        $type2 = str_replace(";base64","",$type1);
        return $type2;
    }

    public static function getMimeType($file){
        $split = explode ( "/" , self::getMime($file));
        return $split[0];
    }

    public static function getMimeSubType($file){
        $split = explode ( "/" , self::getMime($file));
        return $split[1];
    }

    public static function Base64Decode($file){
        return base64_decode(self::getSuffix($file));
    }

    public static function CompanyLogo()
    {
        $logo = FileStore::where('id', SettingHelper::GetSetting('companylogo'))->first();
        if (count($logo) === 1) {
            return $logo->file;
        } else {
            return "";
        }
    }

    public static function CompanyLogoWithRedundancy()
    {
        $logo = FileStore::where('id', SettingHelper::GetSetting('companylogo'))->first();
        if (count($logo) === 1) {
            return $logo->file;
        } else {
            switch (SettingHelper::GetSetting('system-type')) {
                case "NonProfit":
                    return "/images/oslogo-non-profit.png";
                    break;
                default:
                    return "/images/oslogo.png";
            }
        }
    }


}