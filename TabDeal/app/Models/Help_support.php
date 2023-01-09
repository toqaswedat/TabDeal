<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Help_support extends Model
{
    use HasFactory;
    protected $table = 'help_support';
    protected $fillable = ["user_id","name","subject","detail","email","created_at","updated_at"];
}
