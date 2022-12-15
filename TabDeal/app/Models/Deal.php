<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    protected $table = 'deals';
    protected $fillable = ["deal_type","dealmaker_userid ","dealmaker_userid ","mydeals_userid","seller_userid","dealmaker_itemid","owner_itemid ","againstowner_itemid","quantity",
    "againstowner_quantity","deal_price","againstowner_dealprice","unit","dealmaker_offerprice","time_period","status","accepted_dateandtime"
    ,"disputed_datetime","created_at","updated_at"];
}
