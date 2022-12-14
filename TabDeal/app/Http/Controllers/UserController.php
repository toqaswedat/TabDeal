<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Front_user;
use App\Models\Frontuser_dealreview;
use App\Models\Item_favorite;
use App\Models\Wallet_transaction;
use Exception;
use Illuminate\Http\Request;



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
            // $user=Front_user::where('id',);
           
            }
            catch(Exception $ex)
            {
                return $ex->getMessage();
            } 
    }
}
