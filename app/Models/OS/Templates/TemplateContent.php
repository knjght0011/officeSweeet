<?php

namespace App\Models\OS\Templates;

use Illuminate\Database\Eloquent\Model;

class TemplateContent extends Model
{
    protected $table = 'templatecontent';

    protected $fillable = array('content','template_id');

    public function template()
    {
        return $this->belongsTo('App\Models\Template');
    }



}
