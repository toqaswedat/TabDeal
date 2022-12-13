<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topics extends Model
{
    use HasFactory;
    protected $table = 'topics';
    protected $fillable = ['id', 'content_url', 'title_ar', 'title_en', 'details_ar', 'details_en', 'date', 'expire_date', 'video_type', 'photo_file', 'attach_file', 'video_file', 
        'audio_file', 'icon', 'status', 'visits', 'webmaster_id', 'section_id', 'row_no',
        'seo_title_ar', 'seo_title_en', 'seo_description_ar', 'seo_description_en', 'seo_keywords_ar', 'seo_keywords_en', 'seo_url_slug_ar', 'seo_url_slug_en','created_by','updated_by','created_at','updated_at'
    ];
}
