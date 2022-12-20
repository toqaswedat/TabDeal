<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function loginUser(Request $request){
        try{
            if($request->email){
            if(Auth::attempt($request->only('email', 'password'))){
                $user = User::where('email', $request->email)->first();
                $frontUser=Front_user::where('email',$user->email)->first();
                return response()->json([
                    'user_id'=>$user->id,
                    'name'=>$user->name,
                    'user_type'=>$frontUser->eMemberType,
                    'points'=>$frontUser->current_balance,
                    'business_name'=>$frontUser->business_name,
                    'business_name'=>$frontUser->business_name,
                    'status'=>$frontUser->status,
                    'result' => 'true',
                    'message'=>'login Successfully',
                    // 'token' => $user->createToken("API TOKEN")->plainTextToken
                ]);
            }else{
                return response()->json([
                    'result' => 'false',
                    'message'=>'Login Failed',
                ]);
            }
        }else{
            return response()->json([
                'result' => 'false',
                'message'=>'Fill Missing Field',
            ]);
        }
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
}
