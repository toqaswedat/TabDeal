<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Chatroom;
use App\Models\Front_user;
use App\Models\Deal_Disputes;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }
    public function updateTrade(Request $request)
    {
        try {
            // return $request->user_id;
            $Deals = Deal::where('id', $request->trade_id)->update([
                'owner_itemid' => $request->offer_id,
                'quantity' => $request->offer_qty,
                'againstowner_itemid' => $request->barter_offer_id,
                'againstowner_quantity' => $request->barter_offer_qty,
                'dealmaker_offerprice' => $request->points,
            ]);
            if ($Deals) {
                return response()->json([
                    'result' => true
                ]);
            } else {
                return response()->json([
                    'result' => false,
                    'message' => ""
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function changeStatus(Request $request)
    {
        try {
            function sendnotification($user_id, $text, $from_id = 0, $trade_id = 0, $type = '')
            {
                Notification::create([
                    'from_id' => $from_id,
                    'notification' => $text,
                    'user_id' => $user_id,
                    'type' => $type,
                    'trade_id' => $trade_id,
                    'seen' => 0
                ]);
            }
            if ($request->user_id && $request->trade_id && $request->status) {
                $oldstatus = Deal::where('id', $request->trade_id)->get('status');
                if ($oldstatus != $request->status) {
                    $updatestatus = Deal::where('id', $request->trade_id)->update(['status' => $request->status]);
                    if ($updatestatus) {
                        // return 123;
                        $id = $request->trade_id;

                        switch ($request->status) {
                            case 'CONFIRMED':
                                // confirm
                                sendnotification($request->to_id, 'Your Trade is Confirmed', $request->user_id, $id);
                                break;
                            case 'REJECTED':
                                // reject
                                sendnotification($request->to_id, 'Your trade offer is rejected', $request->user_id, $id);
                                break;
                            case 'CANCELLED':
                                // cancek
                                sendnotification($request->to_id, 'Your trade is cancelled', $request->user_id, $id);
                                break;
                            case 'ACCEPTED':
                                // close
                                sendnotification($request->to_id, 'Your trade is now closed', $request->user_id, $id);
                                break;
                        }
                        return response()->json([
                            'result' => true,
                            'message' => 'Updated Successfully'
                        ]);
                    }
                } else {
                    return response()->json([
                        'result' => false,
                    ]);
                }
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'Invalid Input Data'
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function add_trade(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'owner_itemid' => 'required|int',
                'againstowner_itemid' => 'required|int',
                'mydeals_userid' => 'required|int',
                'deal_type' => 'required|in:OFFER,DEMAND,SWAP',
                'quantity' => 'nullable|int',
                'dealmaker_offerprice' => 'nullable|string',
                'dealmaker_itemid' => 'nullable|int',
                'againstowner_quantity' => 'nullable|int',
                'againstowner_dealprice' => 'nullable|string',
                'deal_price' => 'nullable|string',
                'dealmaker_userid' => 'nullable|int',
                'seller_userid' => 'nullable|int'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            } else {
                $newTrade = Deal::create([
                    'owner_itemid' => $request->owner_itemid,
                    'againstowner_itemid' => $request->againstowner_itemid,
                    'mydeals_userid' => $request->mydeals_userid,
                    'deal_type' => $request->deal_type,
                    'quantity' => $request->quantity,
                    'dealmaker_offerprice' => $request->dealmaker_offerprice,
                    'dealmaker_itemid' => $request->dealmaker_itemid,
                    'againstowner_quantity' => $request->againstowner_quantity,
                    'againstowner_dealprice' => $request->againstowner_dealprice,
                    'deal_price' => $request->deal_price,
                    'dealmaker_userid' => $request->dealmaker_userid,
                    'seller_userid' => $request->seller_userid
                ]);
                if($newTrade){
                    $lastInsertedRecordDeal = Deal::latest()->first();
                    $id = $lastInsertedRecordDeal->id;
                    // return $id;
                    Chatroom::create([
                        'deal_id' => $id,
                        'sender_id' => $request->dealmaker_userid,
                        'receiver_id' => $request->mydeals_userid,
                        'item_id' => $request->owner_itemid
                    ]);
                    $lastInsertedRecordChat = Chatroom::latest()->first();
                    $idTwo = $lastInsertedRecordChat->id;
                    // return $idTwo;
                    Message::create([
                        'chatroom_id' => $idTwo,
                        'sender_id' => $request->dealmaker_userid,
                        'receiver_id' => $request->mydeals_userid,
                        'message' => $request->message,
                        'msg_type' => 'msg'
                    ]);  
                    Notification::create([
                        'from_id' => $request->to_id,
                        'notification' => $request->message,
                        'user_id' => $request->user_id,
                        'type' => '',
                        'trade_id' => $id
                    ]);
                    $receiver = Front_user::where('id', $request->dealmaker_userid)->get('email');
                    // sendmail($receiver,"Tabdeal Trade", "You have a Tabdeal trade, please check it.");
                }
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        }

     // start endpoint add_trade_demand_point
         public function add_trade_demand_point(Request $request){
            try{
                function sendnotifications($user_id, $text, $from_id = 0, $trade_id = 0, $type = ''){
                    Notification::create([
                        'from_id' => $from_id,
                        'notification' => $text,
                        'user_id' => $user_id,
                        'type' => $type,
                        'trade_id' => $trade_id,
                        'seen' => 0
                    ]);
                    
                }
                $validator = Validator::make($request->all(), [
                    'owner_itemid' => 'required|integer',
                    'mydeals_userid' => 'required|integer',
                    'dealmaker_userid' => 'required|integer',
                    'message' => 'required|string',
                    'deal_type' => 'required|string',
                    'quantity' => 'required|integer',
                    'deal_price' => 'required|integer',
                    'dealmaker_offerprice' => 'nullable|string',
                    'seller_userid' => 'required|integer',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'result' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }else{
                $Deals=Deal::create([
                    'owner_itemid'=>$request->owner_itemid,
                    'mydeals_userid'=>$request->mydeals_userid,
                    'dealmaker_userid'=>$request->dealmaker_userid,
                    'message'=>$request->message,
                    'deal_type'=>$request->deal_type,
                    'quantity'=>$request->quantity,
                    'deal_price'=>$request->deal_price,
                    'dealmaker_offerprice'=>$request->dealmaker_offerprice,
                    'seller_userid'=>$request->seller_userid,
                ]);
                $chat_rooms=Chatroom::create([
                    'deal_id'=>$Deals->id,
                    'sender_id'=>$request->dealmaker_userid,
                    'receiver_id'=>$request->mydeals_userid,
                    'item_id'=>$request->owner_itemid,
                ]);
                $messages=Message::create([
                    'chatroom_id'=>$chat_rooms->id,
                    'sender_id'=>$request->dealmaker_userid,
                    'receiver_id'=>$request->mydeals_userid,
                    'message'=>$request->message,
                    'msg_type'=>'msg',
                ]);
                // sendnotifications($request->dealmaker_userid, $request->message, $request->mydeals_userid, $chat_rooms->id);
                $EmailUser = Front_user::where('id', $request->dealmaker_userid)->get()->first();
                
                // send email 
                // $data = ['message' => 'You started a Tabdeal trade'];
    
                // Mail::send('mail@tabdeal.online', $data, function($message,$EmailUser)
                // {
                //     $message->to($EmailUser->email, $EmailUser->vFirstName)
                //             ->subject('Tabdeal Trade');
                // });
                }
                if($Deals){
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
            catch(Exception $ex)
            {
                return $ex->getMessage();
            }
    }
    // end endpoint add_trade_demand_point

    // start endpoint add_trade_offer_point
    public function add_trade_offer_point(Request $request){
        try{
            function sendnotifications1($user_id, $text, $from_id = 0, $trade_id = 0, $type = ''){
                Notification::create([
                    'from_id' => $from_id,
                    'notification' => $text,
                    'user_id' => $user_id,
                    'type' => $type,
                    'trade_id' => $trade_id,
                    'seen' => 0
                ]);   
            }
            $validator = Validator::make($request->all(), [
                'mydeals_userid' => 'required|integer',
                'dealmaker_userid' => 'required|integer',
                'dealmaker_offerprice' => 'nullable|string',
                'againstowner_itemid' => 'required|integer',
                'owner_itemid' => 'required|integer',
                'message' => 'required|string',
                'deal_type' => 'required|string',
                'quantity' => 'required|integer',
                'deal_price' => 'required|integer',
                'seller_userid' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }else{
            $Deals=Deal::create([
                'mydeals_userid'=>$request->mydeals_userid,
                'dealmaker_userid'=>$request->dealmaker_userid,
                'dealmaker_offerprice'=>$request->dealmaker_offerprice,
                'againstowner_itemid'=>$request->againstowner_itemid,
                'owner_itemid'=>$request->owner_itemid,
                'message'=>$request->message,
                'deal_type'=>$request->deal_type,
                'quantity'=>$request->quantity,
                'deal_price'=>$request->deal_price,
                'seller_userid'=>$request->seller_userid,
            ]);
            $chat_rooms=Chatroom::create([
                'deal_id'=>$Deals->id,
                'sender_id'=>$request->dealmaker_userid,
                'receiver_id'=>$request->mydeals_userid,
                'item_id'=>$request->owner_itemid,
            ]);
            $messages=Message::create([
                'chatroom_id'=>$chat_rooms->id,
                'sender_id'=>$request->dealmaker_userid,
                'receiver_id'=>$request->mydeals_userid,
                'message'=>$request->message,
                'msg_type'=>'msg',
            ]);
            // sendnotifications1($request->dealmaker_userid, $request->message, $request->mydeals_userid, $chat_rooms->id);
            $EmailUser = Front_user::where('id', $request->dealmaker_userid)->get()->first();
            
            // send email 
            // $data = ['message' => 'You started a Tabdeal trade'];

            // Mail::send('mail@tabdeal.online', $data, function($message,$EmailUser)
            // {
            //     $message->to($EmailUser->email, $EmailUser->vFirstName)
            //             ->subject('Tabdeal Trade');
            // });
    		}
            if($Deals){
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
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    // end endpoint add_trade_offer_point
}