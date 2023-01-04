<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\Comission_setting;
use App\Models\Wallet_transaction;
use Dotenv\Util\Str;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Image;

class AuthController extends Controller
{
    //

    public function loginUser(Request $request)
    {
        try {
            if ($request->email) {
                $credentials = $request->only('email', 'password');
                if (Auth::attempt($credentials)) {
                    $frontUser = Front_user::where('email', $request->email)->first();
                    return response()->json([
                        'eIsVerified' => $frontUser->eIsVerified,
                        'user_id' => $frontUser->id,
                        'name' => $frontUser->vFirstName . " " . $frontUser->vLastName,
                        'user_type' => $frontUser->eMemberType,
                        'points' => $frontUser->current_balance,
                        'business_name' => $frontUser->business_name,
                        'business_name' => $frontUser->business_name,
                        'status' => $frontUser->status,
                        'result' => 'true',
                        'message' => 'login Successfully',
                        'token' => $frontUser->createToken($request->email)->plainTextToken
                    ]);
                } else {
                    return response()->json([
                        'result' => 'false',
                        'message' => 'Login Failed',
                    ]);
                }
            } else {
                return response()->json([
                    'result' => 'false',
                    'message' => 'Fill Missing Field',
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    } //end of login mechanism



    public function registerUser(Request $request)
    {

        /*
       * This Function is supposed to register users in the DB but not using the users table by laravel's built-in Sanctum authentication system
       * instaed, using a predefined table instead called front_users.
       */
        $email = $request->email;
        $password = password_hash($request->password, PASSWORD_DEFAULT);
        $ch = Front_user::where('email', $request->email)->orWhere('vMobileno', $request->phone)->get();
        $comission_settings = Comission_setting::get('signup_earn'); //**Can't tell if it's associated with the user at this stage
        $verification_code = rand(100000, 999999); //verification number generation
        if (count($ch) == 0) { //number of users with same email and phone 
            if ($request->acc_type == 'Business') { //user is a business
                $newUser =  Front_user::create([ //'DB_field' => [value]
                    'business_name' => $request->business_name,
                    'email' => $email,
                    'password' => $password,
                    'c_code' => '971',
                    'vMobileNo' => $request->phone,
                    'country_id' => $request->country,
                    'city_id' => $request->city,
                    'eMemberType' => $request->acc_type,
                    'vFirstName' => $request->first_name,
                    'vLastName' => $request->last_name,
                    'vAboutMe' => $request->about,
                    'current_balance' => $comission_settings["signup_earn"],
                    'verification_code' => $verification_code,

                ]);
                $newUser->save();
            } //end business creation
            else { //user is an individual
                $newUser =    Front_user::create([ //'DB_field' => [value]

                    'email' => $email,
                    'password' => $password,
                    'c_code' => '971',
                    'vMobileNo' => $request->phone,
                    'country_id' => $request->country,
                    'city_id' => $request->city,
                    'eMemberType' => $request->acc_type,
                    'vFirstName' => $request->first_name,
                    'vLastName' => $request->last_name,
                    'vAboutMe' => $request->about,
                    'current_balance' => $comission_settings["signup_earn"],
                    'verification_code' => $verification_code,
                    'iProfessionsId' => $request->profession,

                ]);
                $newUser->save();
            } //end individual creation

            if ($newUser) { //if user is registered successfully
                $user_id = $newUser->id; //id of new registered user
                // user wallet data 
                Wallet_transaction::create([  //'DB_field' => [value]
                    'user_id' => $user_id,
                    'transaction_amt' => $comission_settings["signup_earn"],
                    'transaction_type' => 'Signup Earn',
                    'message' => 'Welcome Points',
                    'transaction_effect' => 'plus',
                    'balance' => $comission_settings["signup_earn"]
                ]);
                // the section below checks if newUser is a business and then inserts it to the businesses table:
                if ($newUser->acc_type == 'Business') { //check if acc_type is a business



                } //endif

                else {  //if user isn't registered successfully
                    //add handling code later...
                }
            } else { //If users with same phone /email exist

                //add handling code later...
            } //end if (ch== 0)



        }
    } //end function registerUser

    public function testUsers()
    { // A method just to test if the new user is thrown into the table (Will be deleted upon approval)
        $all_users = Front_user::orderBy('created_at', 'desc')->get();
        $result = array(); //user container
        foreach ($all_users as $user) { //loop on all users 
            array_push($result, $user);
        }
        return response()->json([ //encoding...
            "result" => false,
            'all_users' => $result,
        ]);
    }
}
