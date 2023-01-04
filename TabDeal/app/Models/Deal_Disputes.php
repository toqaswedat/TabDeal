<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal_Disputes extends Model
{
    use HasFactory;
    protected $table = 'deal_disputes';
    protected $fillable = ["deal_id","id","frontuser_id","disputereceiver_userid","message","created_at","updated_at"];
}
