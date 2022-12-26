<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'notification';
    protected $fillable = [
        'id', 'from_id', 'notification', 'user_id', 'trade_id', 'type', 'seen', 'timestamp'
    ];

}
