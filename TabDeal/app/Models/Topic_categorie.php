<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic_categorie extends Model
{
    use HasFactory;
    protected $table = 'topic_categories';
    protected $fillable = [ "topic_id", "section_id","created_at","updated_at"];
}
