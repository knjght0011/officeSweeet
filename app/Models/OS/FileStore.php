<?php namespace App\Models\OS;

use App\Helpers\OS\FileStoreHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileStore extends CustomBaseModel
{
    use SoftDeletes;

    protected $table = 'filestore';

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id')->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')->withTrashed();
    }

    public function uploader()
    {
        return $this->belongsTo('App\Models\User', 'upload_user', 'id')->withTrashed();
    }

    public function receipt()
    {
        return $this->hasOne('App\Models\Receipt', "filestore_id", "id");
    }

    public function deposit()
    {
        return $this->hasOne('App\Models\Deposit', "filestore_id", "id");
    }
    public function check()
    {
        return $this->hasOne('App\Models\Check', "filestore_id", "id");
    }

    public function hasLinks(){
        $return = false;
        if(count($this->receipt) > 0){
            $return = true;
        }
        if(count($this->deposit) > 0){
            $return = true;
        }

        return $return;
    }

    public function LinkedTo(){
        if($this->client_id != null){
            return "Client: " . $this->client->getName();
        }
        if($this->vendor_id != null){
            return "Vendor: " . $this->vendor->getName();
        }
        if($this->user_id != null){
            return "User: " . $this->user->getName();
        }
        if(count($this->receipt) === 1) {
            return "Expense";
        }
        if(count($this->deposit) === 1){
            return "Deposit";
        }
        if(count($this->check) === 1){
            return "Check";
        }

        return "None";

    }

    public function getPrefix(){
        return FileStoreHelper::getPrefix($this->file);
    }

    public function getSuffix(){
        return FileStoreHelper::getSuffix($this->file);
    }

    public function getMime(){
        return FileStoreHelper::getMime($this->file);
    }

    public function getMimeType(){
        return FileStoreHelper::getMimeType($this->file);
    }

    public function getMimeSubType(){
        return FileStoreHelper::getMimeSubType($this->file);
    }

    public function Base64Decode(){
        return FileStoreHelper::Base64Decode($this->file);
    }
}
