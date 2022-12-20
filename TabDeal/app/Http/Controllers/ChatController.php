<?php

namespace App\Http\Controllers;

use App\Models\Chatarchive;
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
}
