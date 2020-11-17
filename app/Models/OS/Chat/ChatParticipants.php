<?php

namespace App\Models\OS\Chat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatParticipants extends Model
{
    use SoftDeletes;

    protected $connection = 'subdomain';

    protected $table = "chat_participants";

    public function chatthread()
    {
        return $this->belongsTo('App\Models\OS\Chat\ChatThread', 'chat_threads_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
}
