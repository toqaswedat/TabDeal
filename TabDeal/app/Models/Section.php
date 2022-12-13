<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $table = 'sections';
    protected $fillable = ["title_ar","title_en","icon", "photo" ,"father_id","webmaster_id", "visits","row_no","status",
    "seo_title_ar","seo_title_en","seo_description_ar","seo_description_en","seo_keywords_ar","seo_keywords_en","seo_url_slug_ar","seo_url_slug_en","created_by","updated_by",
    "created_at","updated_at","is_featured"];
}
