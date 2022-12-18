<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_report extends Model
{
    use HasFactory;
    protected $table = 'item_reports';
    protected $fillable = ["userid_reported","item_id","message","created_at","updated_at"];
}
