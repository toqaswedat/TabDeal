<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatarchive extends Model
{
    use HasFactory;
    protected $table = 'chatarchives';
    protected $fillable = ["id","frontuser_id","chatroom_id","created_at","updated_at"];
}
