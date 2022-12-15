<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_favorite extends Model
{
    use HasFactory;
    protected $table = 'item_favorites';
    protected $fillable = ["frontuser_id","item_id","created_at","updated_at"];
}
