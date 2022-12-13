<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet_transaction extends Model
{
    use HasFactory;
    protected $table = 'user_wallet_transaction';
    protected $fillable = ["deal_id","user_id", "transaction_amt", "transaction_type","message","transaction_effect","transaction_date","balance"];
}
