<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'permissions';
    protected $fillable = [
        'name', 'view_status', 'add_status', 'edit_status', 'delete_status', 'analytics_status', 'inbox_status',
        'newsletter_status', 'calendar_status', 'banners_status', 'product_status', 'reported_products',
        'reported_chats', 'disputed_deals', 'dealreviews_status', 'deals', 'contactInquiry_status', 'helpSupport_status', 'customer_status',
        'locations_status', 'professions_status', 'business_category_status', 'settings_status','webmaster_status','data_sections','status'
        ,'created_by','updated_by','created_at','updated_at'
    ];
}
