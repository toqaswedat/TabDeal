<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Front_user extends Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $table = 'front_users';
    protected $fillable = ["iReferalFrontUsersId","eMemberType","user_id","vFirstName", "vLastName", "business_name","email","email_verified_at","password","c_code",
    "vMobileNo","iProfessionsId","vProfilePic","vAboutMe","vNickName","vIdProof","interests","country_id","state_id","city_id"
    ,"vFacebookUrl","vTwitterUrl","vLinkedInUrl","vInstagramUrl","eIsVerified","last_logged_in","status","resetpassword_token","resetpassword_token_expire","access_token",
    "remember_token","eIsDeleted","deleted_at","created_at","updated_at","current_balance","notification_id","verification_code","verify_code_expire"];
}
