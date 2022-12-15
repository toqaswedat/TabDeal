<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countrie extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = ["code","title_ar","title_en", "tel", "visits","row_no","status","created_at","updated_at"];
}
