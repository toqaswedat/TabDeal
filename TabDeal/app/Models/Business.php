<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $table = 'businesses';
    protected $fillable = ['id','vName','c_code','PhoneNo','vLog','iBusinessCategoryId','iFrontUserId','vVatNumber','vBusinessAdress','vBusinessAdress2',
    'vWebsiteAdress','vAboutBusiness','vBusinessDoc1','vBusinessDoc2','vBusinessDoc3','vBusinessDoc4','vBusinessDoc5','created_at','updated_ad'];
}
