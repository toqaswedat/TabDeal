<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

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

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vLastName' => 'nullable|string|max:255',
                'eMemberType' => 'required|string',
                'vMobileNo' => 'nullable|string|max:15',
                'email' => 'required|string|email|max:255',
                'vAboutMe' => 'required|string',
                'iProfessionsId' => 'required|integer',
                'country_id' => 'nullable|integer',
                'city_id' => 'nullable|integer',
                'vFirstName' => 'nullable|string|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            if ($request->id) {
                $ProfileEdit = Front_user::where('id', $request->id)->update([
                    'vFirstName' => $request->vFirstName,
                    'vLastName' => $request->vLastName,
                    'vMobileNo' => $request->vMobileNo,
                    'vAboutMe' => $request->vAboutMe,
                    'iProfessionsId' => $request->iProfessionsId,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'email' => $request->email,
                    'eMemberType' => $request->eMemberType,
                ]);
                if ($ProfileEdit) {
                    return response()->json([
                        'result' => true,
                        'img' => true,
                        'message' => 'Updated Successfully'
                    ]);
                } else {
                    return response()->json([
                        'result' => false
                    ]);
                }
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'Data missing'
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function updateCurrentBalance(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|int|max:255',
                'current_balance' => 'required|string|max:50',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }


            $balance = Front_user::where('id', $request->id)->update(['current_balance' => $request->current_balance]);
            if ($balance) {
                return response()->json([
                    'result' => true,
                    'message' => 'Updated Successfully'
                ]);
            } else {
                return response()->json([
                    'result' => false,
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function updateProfileBusiness(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'id' => 'required|int|max:1000',
                'vFirstName' => 'nullable|string|max:255',
                'vLastName' => 'nullable|string|max:255',
                'vMobileNo' => 'nullable|string|max:15',
                'vAboutMe' => 'nullable|string',
                'country_id' => 'nullable|integer',
                'city_id' => 'nullable|integer'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }


            $ProfileBusiness = Front_user::where('id', $request->id)->where('eMemberType','Business')->update([
                'vFirstName' => $request->vFirstName,
                'vLastName' => $request->vLastName,
                'vMobileNo' => $request->vMobileNo,
                'vAboutMe' => $request->vAboutMe,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id
            ]);
            if ($ProfileBusiness) {
                return response()->json([
                    'result' => true,
                    'message' => 'Updated Successfully'
                ]);
            } else {
                return response()->json([
                    'result' => false,
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

    }
}
