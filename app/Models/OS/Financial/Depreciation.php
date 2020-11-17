<?php

namespace App\Models\OS\Financial;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Depreciation extends Model
{
    protected $table = 'depreciation';

    protected $dates = ['date'];

    use SoftDeletes;

    public function asset()
    {
        return $this->belongsTo('App\Models\OS\Financial\Asset', 'asset_id', 'id');
    }

}
