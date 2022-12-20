<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Front_user;
use App\Models\Frontuser_dealreview;
use App\Models\Item;
use App\Models\Item_favorite;
use App\Models\Item_image;
use App\Models\Item_report;
use App\Models\User;
use App\Models\Wallet_transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function ledger(Request $request){
        try{
            if($request->user_id){
            $user=Wallet_transaction::where('user_id',$request->user_id)->orderBy('id', 'DESC')->get();
            if($user){
                return response()->json([ 
                    "result"=> true,
                    'ledger'=> $user,
                ]); 
            }else{
            return response()->json([ 
                "result"=> false,
            ]);
            }
            }else {
                return response()->json([ 
                    "result"=> false,
                    'message'=> "User Id not found",
                ]);
            }
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }

    }
    public function rem_favourite(Request $request){
        try{
        $favorite=Item_favorite::where('frontuser_id',$request->user_id)->where('item_id',$request->item_id)->first();
        if($favorite != null){
        $favorite->delete();
        return response()->json([ 
            'status'=> true,
        ]);}
        else
        return response()->json([ 
            'status'=> false,
        ]);
        }
        catch(Exception $ex)
              {
                  return $ex->getMessage();
              }  
    }
    public function get_favourite(Request $request){
        try{
        $favorite=Item_favorite::where('frontuser_id',$request->user_id)->where('item_id',$request->item_id)->first();
        if($favorite != null){
        return response()->json([ 
            'status'=> true,
        ]);}
        else
        return response()->json([ 
            'status'=> false,
        ]);
        }
        catch(Exception $ex)
              {
                  return $ex->getMessage();
              }  
    }
    public function deals_number(Request $request){
        try{
        $Deal=Deal::where('mydeals_userid',$request->user_id)->get();
        $demands=0;
        $offers=0;
        $swap=0;
        foreach ($Deal as $Dealtype) {
        $type=$Dealtype->deal_type;
        if($type=="OFFER"){
            $offers++;
         }
         if($type=="DEMAND"){
            $demands++;
         }
          if($type=="SWAP"){
            $swap++;
         }
        }
        $data= ["offers"=>$offers,"demands"=>$demands,"swap"=>$swap];
        return response()->json([ 
            'result'=> true,
            'deals_num'=>$data
        ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    public function get_item_reviews(Request $request){
        try{
            $profile_url = 'https://tabdeal.online/';
            $rev=array();
            $user=Frontuser_dealreview::where('item_id',$request->item_id)->get();
            foreach($user as $frontuserFor){
            $userFront1=Front_user::where('id',$frontuserFor->userid_receiver)->get();
            $userFront=$userFront1[0]->vFirstName." ".$userFront1[0]->vLastName;
            $userArrayReviews=array();
            $time = strtotime($frontuserFor->created_at);
            $newformat = date('Y-m-d',$time);
            $time2 = strtotime($frontuserFor->updated_at);
            $newformat2 = date('Y-m-d h:m:s',$time2);
            $time3 = strtotime($userFront1[0]->created_at);
            $newformat3 = date('d M, Y',$time3);
            $userArrayReviews["id"]=$frontuserFor->id;
            $userArrayReviews["deal_id"]=$frontuserFor->deal_id;
            $userArrayReviews["item_id"]=$frontuserFor->item_id;
            $userArrayReviews["userid_post"]=$frontuserFor->userid_post;
            $userArrayReviews["rating"]=$frontuserFor->rating;
            $userArrayReviews["review"]=$frontuserFor->review;
            $userArrayReviews["created_at"]=$newformat;
            $userArrayReviews["updated_at"]=$newformat2;
            $userArrayReviews["is_approved"]=$frontuserFor->is_approved;
            $userArrayReviews["username"]=$userFront;
            $userArrayReviews["user_image"]=$profile_url.'api/uploads/users/'.$userFront1[0]->vProfilePic;
            $userArrayReviews["join_time"]=$newformat3;
            $userArrayReviews["acc_type"]=$userFront1[0]->eMemberType;
            $userArrayReviews["tp"]=0;
            array_push($rev, $userArrayReviews);
            }
            return response()->json([ 
                'result'=> true,
                'rev'=>  $rev
            ]);
            }
            catch(Exception $ex)
            {
                return $ex->getMessage();
            } 
    }
    public function get_item_deals(Request $request){
        try{
            // $deals=array();
        $Deal=Deal::where('dealmaker_userid',$request->user_id)->where('owner_itemid',$request->item_id)->get();
            //  $deals["u"]=$Deal;
            //  $r="2021-11-01 21:13:35";
            //  $deals["u"][0]->created_at="2021-11-01";
            // // return $Deal[0]->id;
        return response()->json([ 
            'result'=> true,
            'deals'=>$Deal
        ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    public function add_credits(Request $request){
        try{
            $newCredit=Wallet_transaction::create([
                'user_id'=>$request->user_id,
                'deal_id'=>$request->deal_id,
                'transaction_amt'=>$request->transaction_amt,
                'transaction_type'=>$request->transaction_type,
                'message'=>$request->message,
                'transaction_effect'=>$request->transaction_effect,
                'balance'=>$request->balance,
            ]);
            if ($newCredit){
            return response()->json([ 
                'result'=> true,
            ]);
            }
            else {
                return response()->json([ 
                    'result'=> false,
                ]);
            }
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    public function automatch(Request $request){
        try{
            $profile_url = 'https://tabdeal.online/';
            $user=Item::where('itemsectionsubcategoryid',$request->id)->where('frontuser_id','!=',$request->user_id)->where('offerdemandswap',$request->swap_type)->get();
            $automatch=array();
            $imagesArray=array();
            foreach($user as $frontuserFor){
                $userFront1=Front_user::where('id',$frontuserFor->frontuser_id)->get();
                $userFront=$userFront1[0]->vFirstName." ".$userFront1[0]->vLastName;
                $userArrayReviews=array();
                $time = strtotime($frontuserFor->created_at);
                $newformat = date('Y-m-d',$time);
                $time2 = strtotime($frontuserFor->updated_at);
                $newformat2 = date('Y-m-d h:m:s',$time2);
                $time3 = strtotime($userFront1[0]->created_at);
                $newformat3 = date('d M, Y',$time3);
                $Images=Item_image::where('item_id',$frontuserFor->id)->get();
                foreach($Images as $Image){
                        $imagesArray1=$Image->name;
                array_push($imagesArray, $imagesArray1);
                }
                $userArrayReviews["id"]=$frontuserFor->id;
                $userArrayReviews["itemsectioncategoryid"]=$frontuserFor->itemsectioncategoryid;
                $userArrayReviews["itemsectionsubcategoryid"]=$frontuserFor->itemsectionsubcategoryid;
                $userArrayReviews["frontuser_id"]=$frontuserFor->frontuser_id;
                $userArrayReviews["title"]=$frontuserFor->title;
                $userArrayReviews["description"]=$frontuserFor->description;
                $userArrayReviews["preferred_item"]=$frontuserFor->preferred_item;
                $userArrayReviews["itemtype"]=$frontuserFor->itemtype;
                $userArrayReviews["offerdemandswap"]=$frontuserFor->offerdemandswap;
                $userArrayReviews["mbu"]=$frontuserFor->mbu;
                $userArrayReviews["unit"]=$frontuserFor->unit;
                $userArrayReviews["itemtags"]=$frontuserFor->itemtags;
                $userArrayReviews["country_id"]=$frontuserFor->country_id;
                $userArrayReviews["state_id"]=$frontuserFor->state_id;
                $userArrayReviews["city_id"]=$frontuserFor->city_id;
                $userArrayReviews["status"]=$frontuserFor->status;
                $userArrayReviews["item_status"]=$frontuserFor->item_status;
                $userArrayReviews["statusupdatetime"]=$frontuserFor->statusupdatetime;
                $userArrayReviews["poststatusupdate"]=$frontuserFor->poststatusupdate;
                $userArrayReviews["totalview"]=$frontuserFor->totalview;
                $userArrayReviews["created_at"]=$newformat;
                $userArrayReviews["updated_at"]=$newformat2;
                $userArrayReviews["username"]=$userFront;
                $userArrayReviews["user_image"]=$profile_url.'api/uploads/users/'.$userFront1[0]->vProfilePic;
                $userArrayReviews["join_time"]=$newformat3;
                $userArrayReviews["acc_type"]=$userFront1[0]->eMemberType;
                $userArrayReviews["tp"]=0;
                $userArrayReviews["post_type"]=strtolower($frontuserFor->itemtype);
                $userArrayReviews["thumb"]=strtolower($frontuserFor->itemtype);
                $userArrayReviews["image"]=$imagesArray;
                $imagesArray=array();
                array_push($automatch, $userArrayReviews);
                }
                return response()->json([ 
                    'automatch'=>$automatch
                ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    public function save_profile(Request $request){
        try{
        if($request->img != NULL && $request->img != 'assets/images/profile.png')
        return response()->json([ 
            'result'=> true,

        ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    
}
