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

    //  Two endPoint Get_offers  and Get_demand  
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
             if($request->last_id)
             $last_Id=$request->last_id;
             else{
                 $last_Id1=Item::skip(12)->take(1)->get('id');
                $last_Id=$last_Id1[0]->id;
             }
             $Items = Item::where('offerdemandswap', $request->itemtype)
            ->where('description','like',"%$request->search%")
            ->where('id','<',$last_Id)
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
                $userArrayReviews["cityName_ar"] =$cityName->title_ar;
                $userArrayReviews["username"] = $userFront;
                $userArrayReviews["user_image"] = $profile_url . 'api/uploads/users/' . $userFront1[0]->vProfilePic;
                $userArrayReviews["join_time"] = $newformat3;
                $userArrayReviews["acc_type"] = $userFront1[0]->eMemberType;
                $userArrayReviews["created_date"] = $Item->statusupdatetime;
                $userArrayReviews["tp"] = $Item->mbu ? $Item->mbu : 0;
                $userArrayReviews["time_created"] = to_time_ago(strtotime($Item->created_at));
                $userArrayReviews["time_created_ar"] = to_time_ago_ar(strtotime($Item->created_at));
                $userArrayReviews["post_type"] = strtolower($Item->itemtype);
                if(count($imagesArray)>0)
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' . $imagesArray[0];
                else
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' ;
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
   //  endpoint Get_offers


   //  Two endPoint Get_cat_demand  and Get_cat_offers 
    public function get_cat_offers(Request $request)
    {
        try {
            function to_time_ago_ar1( $time ){
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
             function to_time_ago1( $time ){
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
             if($request->last_id)
             $last_Id=$request->last_id;
             else{
                 $last_Id1=Item::skip(12)->take(1)->get('id');
                $last_Id=$last_Id1[0]->id;
             }
            $Items = Item::where('itemsectioncategoryid',$request->category)
            ->where('itemsectionsubcategoryid',$request->subcategory)
            ->where('id','<',$last_Id)
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
                $userArrayReviews["countryName_ar"] =$countryName->title_ar;
                $userArrayReviews["countryName_en"] =$countryName->title_en;
                $userArrayReviews["cityName_en"] =$cityName->title_en;
                $userArrayReviews["cityName_ar"] =$cityName->title_ar;
                $userArrayReviews["username"] = $userFront;
                $userArrayReviews["user_image"] = $profile_url . 'api/uploads/users/' . $userFront1[0]->vProfilePic;
                $userArrayReviews["join_time"] = $newformat3;
                $userArrayReviews["acc_type"] = $userFront1[0]->eMemberType;
                $userArrayReviews["created_date"] = $Item->statusupdatetime;
                $userArrayReviews["tp"] = $Item->mbu ? $Item->mbu : 0;
                $userArrayReviews["time_created"] = to_time_ago1(strtotime($Item->created_at));
                $userArrayReviews["time_created_ar"] = to_time_ago_ar1(strtotime($Item->created_at));
                $userArrayReviews["post_type"] = strtolower($Item->itemtype);
                if(count($imagesArray)>0)
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' . $imagesArray[0];
                else
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' ;
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
   //  endpoint get_cat_offers


   // start endpoint All_load_more
   public function all_load_more(Request $request)
   {
       try {
         function to_time_ago_ar2( $time ){
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
         function to_time_ago2( $time ){
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
         if($request->last_id)
         $last_Id=$request->last_id;
         else{
             $last_Id1=Item::skip(12)->take(1)->get('id');
            $last_Id=$last_Id1[0]->id;
         }
         $Items = Item::where('id','<',$last_Id)
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
             $userArrayReviews["countryName_ar"] =$countryName->title_ar;
             $userArrayReviews["countryName_en"] =$countryName->title_en;
             $userArrayReviews["cityName_en"] =$cityName->title_en;
             $userArrayReviews["cityName_ar"] =$cityName->title_ar;
             $userArrayReviews["username"] = $userFront;
             $userArrayReviews["user_image"] = $profile_url . 'api/uploads/users/' . $userFront1[0]->vProfilePic;
             $userArrayReviews["join_time"] = $newformat3;
             $userArrayReviews["acc_type"] = $userFront1[0]->eMemberType;
             $userArrayReviews["created_date"] = $Item->statusupdatetime;
             $userArrayReviews["tp"] = $Item->mbu ? $Item->mbu : 0;
             $userArrayReviews["time_created"] = to_time_ago2(strtotime($Item->created_at));
             $userArrayReviews["time_created_ar"] = to_time_ago_ar2(strtotime($Item->created_at));
             $userArrayReviews["post_type"] = strtolower($Item->itemtype);
             if(count($imagesArray)>0)
             $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' . $imagesArray[0];
             else
             $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' ;
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
   // end endpoint All_load_more


   // start endpoint search
   public function search(Request $request)
   {
       try {
         function to_time_ago_ar3( $time ){
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
         function to_time_ago3( $time ){
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
         if($request->last_id)
            $last_Id=$request->last_id;
         else{
            $last_Id1=Item::skip(12)->take(1)->get('id');
            $last_Id=$last_Id1[0]->id;
         }
         $Items = Item::where('id','<',$last_Id)
         ->where('itemtype',$request->itype)
         ->where('description','like',"%$request->search%")
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
             $userArrayReviews["countryName_ar"] =$countryName->title_ar;
             $userArrayReviews["countryName_en"] =$countryName->title_en;
             $userArrayReviews["cityName_en"] =$cityName->title_en;
             $userArrayReviews["cityName_ar"] =$cityName->title_ar;
             $userArrayReviews["username"] = $userFront;
             $userArrayReviews["user_image"] = $profile_url . 'api/uploads/users/' . $userFront1[0]->vProfilePic;
             $userArrayReviews["join_time"] = $newformat3;
             $userArrayReviews["acc_type"] = $userFront1[0]->eMemberType;
             $userArrayReviews["created_date"] = $Item->statusupdatetime;
             $userArrayReviews["tp"] = $Item->mbu ? $Item->mbu : 0;
             $userArrayReviews["time_created"] = to_time_ago3(strtotime($Item->created_at));
             $userArrayReviews["time_created_ar"] = to_time_ago_ar3(strtotime($Item->created_at));
             $userArrayReviews["post_type"] = strtolower($Item->itemtype);
             if(count($imagesArray)>0)
             $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' . $imagesArray[0];
             else
             $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' ;
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
   // end endpoint search


      // start endpoint search_load_more
      public function search_load_more(Request $request)
      {
          try {
            function to_time_ago_ar4( $time ){
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
            function to_time_ago4( $time ){
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
            if($request->last_id)
               $last_Id=$request->last_id;
            else{
               $last_Id1=Item::skip(12)->take(1)->get('id');
               $last_Id=$last_Id1[0]->id;
            }

            $order = $request->itype == 'asc' || $request->itype == 'desc' ? $request->itype : 'desc';
            $orderby = $request->itype == 'asc' || $request->itype == 'desc' ? 'mbu' : 'id';
            $itype = $request->itype == 'asc' || $request->itype == 'desc' ? '' : $request->itype;
            $last_mbu=$request->last_mbu;
            
            $Items = Item::where('id', '<', $last_Id)
                ->where(function($query) use ($itype,$last_mbu) {
                    if ($itype) {
                        $query->where('itemtype', $itype);
                    } else {
                        $query->where('mbu', '<=', $last_mbu)
                            ->where('offerdemandswap', 'OFFERED');
                    }
                })
                ->where(function($query) use ($request) {
                    $query->where('description', 'like', "%$request->search%")
                        ->orWhere('title', 'like', "%$request->search%");
                })
                ->orderBy($orderby, $order)->skip(0)->take(12)->get();
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
                $userArrayReviews["countryName_ar"] =$countryName->title_ar;
                $userArrayReviews["countryName_en"] =$countryName->title_en;
                $userArrayReviews["cityName_en"] =$cityName->title_en;
                $userArrayReviews["cityName_ar"] =$cityName->title_ar;
                $userArrayReviews["username"] = $userFront;
                $userArrayReviews["user_image"] = $profile_url . 'api/uploads/users/' . $userFront1[0]->vProfilePic;
                $userArrayReviews["join_time"] = $newformat3;
                $userArrayReviews["acc_type"] = $userFront1[0]->eMemberType;
                $userArrayReviews["created_date"] = $Item->statusupdatetime;
                $userArrayReviews["tp"] = $Item->mbu ? $Item->mbu : 0;
                $userArrayReviews["time_created"] = to_time_ago4(strtotime($Item->created_at));
                $userArrayReviews["time_created_ar"] = to_time_ago_ar4(strtotime($Item->created_at));
                $userArrayReviews["post_type"] = strtolower($Item->itemtype);
                if(count($imagesArray)>0)
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' . $imagesArray[0];
                else
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' ;
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
      // end endpoint search_load_more


}
