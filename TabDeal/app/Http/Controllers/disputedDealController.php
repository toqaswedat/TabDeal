<?php

namespace App\Http\Controllers;

use App\Models\Chat_rooms;
use App\Models\Deal_Disputes;
use App\Models\Deal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class disputedDealController extends Controller
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

    public function disputed_deal(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'deal_id' => 'required|int',
                'frontuser_id' => 'required|int',
                'disputereceiver_userid' => 'required|int',
                'message' => 'nullable|string'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            } else {
                $disputedDeal = Deal::where('id', $request->deal_id)->where('status', '!=', 'DISPUTED')->get();
                $dealMakers = Chat_rooms::where('deal_id', $request->deal_id)
                    ->where(function ($query) use ($request) {
                        $query->where('sender_id', $request->frontuser_id)
                            ->where('receiver_id', $request->disputereceiver_userid);
                    })
                    ->orWhere(function ($query) use ($request) {
                        $query->where('sender_id', $request->disputereceiver_userid)
                            ->where('receiver_id', $request->frontuser_id);
                    })
                    ->get();
                if ($disputedDeal &&  $dealMakers) {
                    $newDisputedDeal = Deal_Disputes::create([
                        'deal_id' => $request->deal_id,
                        'frontuser_id' => $request->frontuser_id,
                        'disputereceiver_userid' => $request->disputereceiver_userid,
                        'message' => $request->message
                    ]);
                    if ($newDisputedDeal) {
                        Deal::where('id', $request->deal_id)->update([
                            'status' => 'DISPUTED'
                        ]);
                    }
                    if ($newDisputedDeal) {
                        return response()->json([
                            'result' => true,
                            'message' => 'Added Successfully'
                        ]);
                    } else {
                        return response()->json([
                            'result' => false,
                        ]);
                    }
                } else {
                    return response()->json([
                        'result' => false,
                    ]);
                }
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
