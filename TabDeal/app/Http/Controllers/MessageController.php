<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Post;


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

    public function updateMessage(Request $request)
    {
        try {
            $messages = Message::where('chatroom_id', $request->chat_id)->update(['is_read' => 1]);
            if ($messages) {
                return response()->json([
                    'result' => true
                ]);
            } else {
                return response()->json([
                    'result' => false
                ]);
            }
            // return Message::where('chatroom_id', $request->chatroom_id)->get();
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
