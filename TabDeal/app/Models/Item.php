<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'id', 'itemsectioncategoryid', 'itemsectionsubcategoryid', 'frontuser_id', 'title', 'description', 'preferred_item',
        'itemtype', 'offerdemandswap', 'mbu', 'quantity', 'unit',
        'itemtags', 'country_id', 'state_id', 'city_id', 'status', 'item_status', 'statusupdatetime',
        'poststatusupdate', 'totalview', 'created_at', 'updated_at'
    ];
}
