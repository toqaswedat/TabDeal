<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business_category extends Model
{
    use HasFactory;
    protected $table = 'business_categories';
    protected $fillable = ["section_id","title_ar","title_en","status", "visits","row_no","created_by","updated_by","created_at","updated_at"];
}
