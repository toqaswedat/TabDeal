<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_rooms extends Model
{
    use HasFactory;
    protected $table = 'chat_rooms';
    protected $fillable = ["deal_id","id","sender_id","receiver_userid","item_id","againstowner_item_id","updated_at","created_at"];
}
