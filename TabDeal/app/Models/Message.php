<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $fillable = [
        'id', 'chatroom_id', 'sender_id', 'receiver_id', 'message', 'is_read', 'latitude',
        'longitude', 'image', 'msg_type', 'created_at', 'updated_at'
    ];
}
