<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{

    use SoftDeletes;

    public function thread()
    {
        return $this->belongsTo(ChatThread::class);
    }
}
