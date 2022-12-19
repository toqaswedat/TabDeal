<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
use Illuminate\Http\Request;
use Exception;

class ProfileController extends Controller
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
    public function getUsersProfile(Request $request)
    {


        try {
            $profile_url = 'https://tabdeal.online/';

            $users = Front_user::where('id', $request->id)->first();
            if ($users) {
                $users["image"] = $profile_url . 'api/uploads/users/' . $users->vProfilePic;
            }
            return response()->json([
                'profile' => $users
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
