<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Exception;

class MessageController extends Controller
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
    public function getMessageUnseen(Request $request)
    {
        try {

            $messages = Message::where('receiver_id', $request->receiver_id)->where('is_read', 0)->get();
            return response()->json([
                'count' => count($messages)
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    
    
}
