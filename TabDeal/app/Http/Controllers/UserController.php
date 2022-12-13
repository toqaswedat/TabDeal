<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
use App\Models\Wallet_transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{
    //

    public function ledger(Request $request){
        try{
            if($request->user_id){
            $user=Wallet_transaction::where('user_id',$request->user_id)->orderBy('id', 'DESC')->get();
            if($user){
                return response()->json([ 
                    "result"=> true,
                    'ledger'=> $user,
                ]); 
            }else{
            return response()->json([ 
                "result"=> false,
            ]);
            }
            }else {
                return response()->json([ 
                    "result"=> false,
                    'message'=> "User Id not found",
                ]);
            }
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }

    }
}
