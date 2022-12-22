<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Notification;
use Illuminate\Http\Request;
use Exception;

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
    public function updateTrade(Request $request){
        try {
            // return $request->user_id;
            $Deals = Deal::where('id', $request->trade_id)->update(['owner_itemid' => $request->offer_id,
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
    public function changeStatus(Request $request){
        try {
            function sendnotification($user_id, $text, $from_id = 0, $trade_id = 0, $type = ''){
                Notification::create([
                    'from_id' => $from_id,
                    'notification' => $text,
                    'user_id' => $user_id,
                    'type' => $type,
                    'trade_id' => $trade_id,
                    'seen' => 0
                ]);
                
            }
            if($request->user_id && $request->trade_id && $request->status){
                $oldstatus = Deal::where('id', $request->trade_id)->get('status');
                if($oldstatus != $request->status){
                $updatestatus = Deal::where('id', $request->trade_id)->update(['status' => $request->status]);
                if($updatestatus){
                    // return 123;
                    $id = $request->trade_id;
                   
                    switch($request->status){
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
                }else{
                    return response()->json([
                        'result' => false,
                    ]);
                }
            }else{
                return response()->json([
                    'result' => false,
                    'message'=> 'Invalid Input Data'
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
        
    }
}

