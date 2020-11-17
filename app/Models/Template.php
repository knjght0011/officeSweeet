<?php

namespace App\Models;

class Template extends CustomBaseModel
{
    protected $table = 'templates';
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function contentversions()
    {
        return $this->hasMany('App\Models\OS\Templates\TemplateContent', 'template_id', 'id');
    }

    public function getcontentAttribute($value)
    {
        if(count($this->contentversions) > 0){

            return $this->contentversions()->latest()->first()->content;

        }else{
            return $value;
        }
    }

}
