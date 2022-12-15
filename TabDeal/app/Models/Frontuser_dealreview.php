<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frontuser_dealreview extends Model
{
    use HasFactory;
    protected $table = 'frontuser_dealreviews';
    protected $fillable = ["deal_id","item_id","userid_post","userid_receiver","rating","review","is_approved","created_at","updated_at"];
}
