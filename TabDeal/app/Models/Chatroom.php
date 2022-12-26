<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatroom extends Model
{
    use HasFactory;
    protected $table = 'chat_rooms';
    protected $fillable = ["deal_id","sender_id","receiver_id","item_id","againstowner_item_id","created_at","updated_at"];
}
