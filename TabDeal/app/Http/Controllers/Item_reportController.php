<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Countrie;
use App\Models\Front_user;
use App\Models\Item;
use App\Models\Item_favorite;
use App\Models\Item_image;
use App\Models\Item_report;
use Exception;
use Illuminate\Http\Request;

class Item_reportController extends Controller
{
    //
    public function report(Request $request)
    {
        try {
            $Items = Item_report::orderBy('id', 'desc')->get();
            return response()->json([
                'data' => $Items
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function get_offers(Request $request)
    {
        try {
            function to_time_ago_ar( $time ){
                $difference = time() - $time;
                if( $difference < 1 )
                {
                   return 'less than only a second ago';
                }
                $time_rule = array (
                   12 * 30 * 24 * 60 * 60 => 'سنة',
                   30 * 24 * 60 * 60 => 'شهر',
                   24 * 60 * 60 => 'يوم',
                   60 * 60 => 'ساعة',
                   60 => 'دقيقة',
                   1 => 'ثانية'
                );
                foreach( $time_rule as $sec => $my_str )
                {
                   $res = $difference / $sec;
                   if( $res >= 1 )
                   {
                      $t = round( $res );
                      return 'منذ' . ' ' . $t .
                      ( $t > 1 ? ' ' : ' ' ) . $my_str ;
                   }
                }
             }
             function to_time_ago( $time ){
                $difference = time() - $time;
                if( $difference < 1 )
                {
                   return 'less than only a second ago';
                }
                $time_rule = array (
                   12 * 30 * 24 * 60 * 60 => 'year',
                   30 * 24 * 60 * 60 => 'month',
                   24 * 60 * 60 => 'day',
                   60 * 60 => 'hour',
                   60 => 'minute',
                   1 => 'second'
                );
                foreach( $time_rule as $sec => $my_str )
                {
                   $res = $difference / $sec;
                   if( $res >= 1 )
                   {
                      $t = round( $res );
                      return $t . ' ' . $my_str .
                      ( $t > 1 ? 's' : '' ) . ' ago';
                   }
                }
             }
            $Items = Item::where('offerdemandswap', $request->itemtype)
            ->where('description','like',"%$request->search%")
            ->where('id','<',$request->last_id)
            ->orwhere('title','like',"%$request->search%")
            ->orderBy('id', 'desc')->skip(0)->take(12)->get();
            $profile_url = 'https://tabdeal.online/';
            $automatch = array();
            $imagesArray = array();
            foreach ($Items as $Item) {
                $userFront1 = Front_user::where('id', $Item->frontuser_id)->get();
                $userFront = $userFront1[0]->vFirstName . " " . $userFront1[0]->vLastName;
                $userArrayReviews = array();
                $time = strtotime($Item->created_at);
                $newformat = date('Y-m-d', $time);
                $time2 = strtotime($Item->updated_at);
                $newformat2 = date('Y-m-d h:m:s', $time2);
                $time3 = strtotime($userFront1[0]->created_at);
                $newformat3 = date('d M, Y', $time3);
                $Images = Item_image::where('item_id', $Item->id)->get();
                $fav = Item_favorite::where('frontuser_id', $request->user_id)->where('item_id',$Item->id)->get();
                $countryName =Countrie::where('id',$Item->country_id)->get()->first();
                $cityName =City::where('id',$Item->city_id)->get()->first();

                foreach ($Images as $Image) {
                    $imagesArray1 = $Image->name;
                    array_push($imagesArray, $imagesArray1);
                }
                
                $userArrayReviews["id"] = $Item->id;
                $userArrayReviews["itemsectioncategoryid"] = $Item->itemsectioncategoryid;
                $userArrayReviews["itemsectionsubcategoryid"] = $Item->itemsectionsubcategoryid;
                $userArrayReviews["frontuser_id"] = $Item->frontuser_id;
                $userArrayReviews["title"] = $Item->title;
                $userArrayReviews["description"] = $Item->description;
                $userArrayReviews["preferred_item"] = $Item->preferred_item;
                $userArrayReviews["itemtype"] = $Item->itemtype;
                $userArrayReviews["offerdemandswap"] = $Item->offerdemandswap;
                $userArrayReviews["mbu"] = $Item->mbu;
                $userArrayReviews["quantity"] =$Item->quantity;
                $userArrayReviews["unit"] = $Item->unit;
                $userArrayReviews["itemtags"] = $Item->itemtags;
                $userArrayReviews["country_id"] = $Item->country_id;
                $userArrayReviews["state_id"] = $Item->state_id;
                $userArrayReviews["city_id"] = $Item->city_id;
                $userArrayReviews["status"] = $Item->status;
                $userArrayReviews["item_status"] = $Item->item_status;
                $userArrayReviews["statusupdatetime"] = $Item->statusupdatetime;
                $userArrayReviews["poststatusupdate"] = $Item->poststatusupdate;
                $userArrayReviews["totalview"] = $Item->totalview;
                $userArrayReviews["created_at"] = $newformat;
                $userArrayReviews["updated_at"] = $newformat2;
                $userArrayReviews["table_name"] = "items";
                $userArrayReviews["fav"] =count($fav);
                $userArrayReviews["countryName_ar"] =$countryName->title_ar;
                $userArrayReviews["countryName_en"] =$countryName->title_en;
                $userArrayReviews["cityName_en"] =$cityName->title_en;
                $userArrayReviews["cityName_ar"] =$cityName->title_en;
                $userArrayReviews["username"] = $userFront;
                $userArrayReviews["user_image"] = $profile_url . 'api/uploads/users/' . $userFront1[0]->vProfilePic;
                $userArrayReviews["join_time"] = $newformat3;
                $userArrayReviews["acc_type"] = $userFront1[0]->eMemberType;
                $userArrayReviews["created_date"] = $Item->statusupdatetime;
                $userArrayReviews["tp"] = $Item->mbu ? $Item->mbu : 0;
                $userArrayReviews["time_created"] = to_time_ago(strtotime($Item->created_at));
                $userArrayReviews["time_created_ar"] = to_time_ago_ar(strtotime($Item->created_at));
                $userArrayReviews["post_type"] = strtolower($Item->itemtype);
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' . $imagesArray[0];
                $userArrayReviews["image"] = $imagesArray;
                $imagesArray = array();
                array_push($automatch, $userArrayReviews);
            }
            return response()->json([
                'data' => $automatch
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
