<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Countrie;
use App\Models\Deal;
use App\Models\Front_user;
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
    public function notifications(Request $request)
    {
        try {
            $profile_url = 'https://tabdeal.online/';
            if($request->user_id){
            $NotificationArray=array();
            $NotificationArrayAll=array();
            $Notifications = Notification::where('user_id', $request->user_id)->orderBy('id', 'DESC')->skip(0)->take(10)->get();
            if(count($Notifications)!=0){
            foreach($Notifications as $Notification){
            $TradeArrayAll=array();

            $From_Notification=Front_user::where('id',$Notification->from_id)->get();
            $From_city=City::where('id',$From_Notification[0]->city_id)->get();
            $From_countrie=Countrie::where('id',$From_Notification[0]->country_id)->get();
            $From_Notification[0]->city=$From_city[0]->title_en;
            $From_Notification[0]->country=$From_countrie[0]->title_en;
            $time = strtotime($From_Notification[0]->created_at);
            $newformat = date('d M, Y',$time);

            $time1 = strtotime($Notification->timestamp);
            $newformat1 = date('H:i',$time1);
            $NotificationArray['id']=$Notification->id;
            $NotificationArray['from_id']=$Notification->from_id;
            $NotificationArray['notification']=$Notification->notification;
            $NotificationArray['user_id']=$Notification->user_id;
            $NotificationArray['trade_id']=$Notification->trade_id;
            $NotificationArray['type']=$Notification->type;
            $NotificationArray['seen']=$Notification->seen;
            $NotificationArray['timestamp']=$Notification->timestamp;
            $NotificationArray['chk']=false;
            $trade=Deal::where('id',$Notification->trade_id)->get();
            array_push($TradeArrayAll, $trade);
            $NotificationArray['trade']=$TradeArrayAll[0];
            $NotificationArray['sender_name']=$From_Notification[0]->vFirstName." ".$From_Notification[0]->vLastName;
            $NotificationArray['join_time']=$newformat;
            $NotificationArray['sender_city']=$From_city[0]->title_en;
            $NotificationArray['sender_country']=$From_countrie[0]->title_en;
            $NotificationArray['sender_user']=$From_Notification[0];
            $NotificationArray['sender_type']=$From_Notification[0]->eMemberType;
            $NotificationArray['sender_img']=$profile_url.'api/uploads/users/'.$From_Notification[0]->vProfilePic;
            $NotificationArray['datetime']=$newformat1;
            array_push($NotificationArrayAll, $NotificationArray);
            }
            return response()->json([
                'result' => true,
                'service'=>$NotificationArrayAll
            ]);
            }else{
                return response()->json([
                    'result' => false
                ]);
            }
            }else {
                return response()->json([
                    'result' => false,
                    'message' => 'User Id not found'
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function updateNotificationSeen(Request $request)
    {
        try {
            $Notifications = Notification::where('user_id', $request->user_id)->update(['seen' => 1]);
            if ($Notifications) {
                return response()->json([
                    'result' => true
                ]);
            } else {
                return response()->json([
                    'result' => false
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function getUnseenNotification(Request $request)
    {
        try {
            $Notifications = Notification::where('user_id', $request->user_id)->where('seen', 0)->get();
            if (count($Notifications) > 0) {
                return response()->json([
                    'result' => true
                ]);
            } else {
                return response()->json([
                    'result' => false
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
