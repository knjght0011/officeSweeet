<?php

namespace App\Models\OS\Templates;

use App\Mail\SendDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

use App\Helpers\OS\FileStoreHelper;

class SigningPage extends Model
{

    protected $connection = 'subdomain';
    protected $table = 'signingpages';

    protected $casts = [
        'positions' => 'array',
    ];

    public function signatures()
    {
        return $this->hasMany('App\Models\OS\Templates\Signature', 'signingpage_id', 'id');
    }

    public function signing()
    {
        return $this->belongsTo('App\Models\OS\Templates\Signing' , 'signing_id', 'id');
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
