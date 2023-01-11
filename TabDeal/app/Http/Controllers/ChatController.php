<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Chatarchive;
use App\Models\Chatreport;
use App\Models\Chatroom;
use App\Models\City;
use App\Models\Countrie;
use App\Models\Deal;
use App\Models\Front_user;
use App\Models\Item;
use App\Models\Item_image;
use App\Models\Message;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function chat_archive(Request $request){
        try{
        if($request->chat_id){
            $chatArchive=Chatarchive::create([
                'frontuser_id'=>$request->user_id,
                'chatroom_id'=>$request->chat_id,
            ]);
            if($chatArchive){
                return response()->json([ 
                    'result'=> true,
                ]);
            }else {
                return response()->json([ 
                    'result'=> false,
                ]);
            }
        }else{
            return response()->json([ 
                'result'=> false,
                'message'=>'Trade Id not found'
            ]);
        }
        }
        catch(Exception $ex)
              {
                  return $ex->getMessage();
              }  
    }

    public function chat_list(Request $request){
        try{
        $profile_url = 'https://tabdeal.online/';
        $ChatforUser=Chatroom::where('sender_id',$request->u_id)->orWhere('receiver_id',$request->u_id)->get();
        $archive = 0;
        $seen = 0;
        $inbox = 0;
        $User=Front_user::where('id',$request->u_id)->get()->first();
        $chat=array();
        $chatALL=array();

        foreach ($ChatforUser as $singelChat) {
            if($singelChat['sender_id'] == $request->u_id) {
                $value = $singelChat['receiver_id'];
            } else {
                $value = $singelChat['sender_id'];
            }
            $UserMeesage=Message::where('chatroom_id',$singelChat['id'])->orderBy('id', 'DESC')->get()->first();
            $unseen=Message::where('chatroom_id',$singelChat['id'])->Where('receiver_id',$request->u_id)->Where('is_read',0)->get();

            $chatArchiveIf=Chatarchive::where('chatroom_id',$singelChat->id)->where('frontuser_id',$request->u_id)->get();
            $trade=Deal::where('id',$singelChat['deal_id'])->get()->first();
            $buyer_username=Front_user::where('id',$trade->dealmaker_userid)->get()->first();
            $seller_username=Front_user::where('id',$trade->mydeals_userid)->get()->first();
            $trade->offer_name=Item::where('id',$trade->singelChat)->get('title')->first();
            $trade->swaper_offer_name=Item::where('id',$trade->dealmaker_itemid)->get('title')->first();
            $trade->offer_user_name=$buyer_username->vFirstName." ".$buyer_username->vLastName;
            
            $offer_img=Item_image::where('item_id',$trade->owner_itemid)->get()->first();

            $fr=Front_user::where('id',$value)->get()->first();
            $frcountry=Countrie::where('id',$fr->country_id)->get('title_en')->first();
            $frcity=City::where('id',$fr->city_id)->get('title_en')->first();

            $fr->country=$frcountry->title_en;
            $fr->city=$frcity->title_en;
    
            if(count($chatArchiveIf) > 0) {
                $singelChat->archive = 1;
                $archive++;
            } else {
                $singelChat->archive = 0;
                    $inbox++;
            }
            if($singelChat->unseen !==0){
                    $seen++;
            }
            $time = strtotime($UserMeesage->created_at);
            $date = date('h:i a',$time);
            $newformat = date('Y-m-d h:m:s',$time);
            $timebuyer = strtotime($buyer_username->created_at);
            $newformat1 = date('Y-m-d',$timebuyer);
            $deal_info['buyer_username']=$buyer_username->vFirstName." ".$buyer_username->vLastName;
            $deal_info['buyer_email']=$buyer_username->email;
            $deal_info['seller_username']=$seller_username->vFirstName." ".$seller_username->vLastName;
            $deal_info['seller_emai']=$seller_username->email;
            $chat['timestamp']=$newformat;
            $chat['unseen']=$unseen;
            $chat['msg']=$UserMeesage->message;
            if($offer_img != NULL){
                $chat['thumb']=$profile_url."api/uploads/items/".$offer_img->name;
                $chat['image']=$chat['thumb'];    
            }else{
                $chat['thumb']='';
                $chat['image']='';
            }
            $chat['trade_id']=$singelChat->deal_id;
            $chat['join_time']=$newformat1;
            $chat['trade']=$trade;
            $chat['deal_info']=$deal_info;
            $chat['archive']=$singelChat->archive;
            $chat['chat_room']=$singelChat;
            $chat['sender_id']=$value;
            $chat['date']=$date;
            $chat['sender_name']=$fr->vFirstName." ".$fr->vLastName;
            $chat['sender_city']=$fr->city;
            $chat['sender_country']=$fr->country;
            $chat['sender_email']=$fr->email;
            $chat['sender_img']=$profile_url."api/uploads/users/".$fr->vProfilePic;
            $chat['sender_user']=$fr;
            $chat['user_image']=$profile_url."api/uploads/users/".$User->vProfilePic;
            $chat['user_id']=$User->id;
            $chat['user_city']=$User->city;
            $chat['username']=$User->vFirstName." ".$User->vLastName;

            array_push($chatALL, $chat);
        }
        return response()->json([ 
            'chat'=>$chatALL,
            'inbox'=>$inbox,
            'seen'=>$seen,
            'archive'=>$archive
        ]);
        }
        catch(Exception $ex)
              {
                  return $ex->getMessage();
              }  
    }

    // start endpoint report_user
    public function report_user(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'message' => 'required|string',
                'userid_report' => 'required|integer',
                'userid_receivereport' => 'required|integer',
                'chatroom_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }else{
            $chatreport=Chatreport::create([
                'message'=>$request->message,
                'userid_receivereport'=>$request->userid_receivereport,
                'userid_report'=>$request->userid_report,
                'chatroom_id'=>$request->chatroom_id,
            ]);
            if($chatreport){
                return response()->json([ 
                    'result'=> true,
                    'message'=>'Added Successfully'
                ]);
            }else {
                return response()->json([ 
                    'result'=> false,
                ]);
            }
        }
        }
        catch(Exception $ex)
              {
                  return $ex->getMessage();
              }  
    }
    // end endpoint report_user


}
