<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    use HasFactory;
    protected $table = 'professions';
    protected $fillable = ["section_id","title_ar","title_en","status", "visits","row_no","created_by","updated_by","created_at","updated_at"];
}
