<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citie extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = ["country_id","title_ar", "title_en", "visits","row_no","status","created_at","updated_at"];
}
