<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Help_support;
use Illuminate\Support\Facades\Validator;


class HelpsupportController extends Controller
{
    public function help(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'subject' => 'required|string',
                'detail' => 'required|string',
                'email' => 'required|email',
                'user_id' => 'required|integer',
            ]);
                
            if ($validator->fails()) {
                // Validation failed
                return response()->json([
                    'error' => $validator->errors(),
                ], 422);
            }
            else{
                $item = help_support::create([
                    'name' => $request->name,
                    'subject' => $request->subject,
                    'detail' => $request->detail,
                    'email' => $request->email,
                    'user_id' => $request->user_id,
                ]);
                
                if ($item) {
                    return response()->json([
                        'result'=>true,
                        'message'=>'Added Successfully',
                        
                    ]);
                  } else {
                    return response()->json([
                        'result'=>false,
                        'message'=>'Added faild',
                        
                    ]);
                  }
                
            }
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
    }
}
