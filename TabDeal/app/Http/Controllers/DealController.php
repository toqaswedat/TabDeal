<?php

namespace App\Http\Controllers;

use App\Models\Deal;
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
}
