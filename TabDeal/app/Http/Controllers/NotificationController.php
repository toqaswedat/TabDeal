<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Exception;

class NotificationController extends Controller
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
    public function updateNotificationSeen(Request $request){
        try {
            $Notifications= Notification::where('user_id', $request->user_id)->update(['seen' => 1]);
            if($Notifications){
                return response()->json([
                    'result' => true
                ]);
            }else{
                return response()->json([
                    'result' => false
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }


    }
}
