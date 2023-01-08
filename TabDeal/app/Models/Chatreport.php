<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatreport extends Model
{
    use HasFactory;
    protected $table = 'chatreports';
    protected $fillable = ["userid_report ",'userid_report',"userid_receivereport","chatroom_id","message","created_at","updated_at"];
}
