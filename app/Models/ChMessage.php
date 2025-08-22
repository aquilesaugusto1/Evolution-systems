<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;

class ChMessage extends Model
{
    use UUID;

    /**
     * Get the user that sent the message.
     */
    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * Get the user that received the message.
     */
    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}