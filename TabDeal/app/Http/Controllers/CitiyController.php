<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Countrie;
use App\Models\Front_user;
use App\Models\Frontuser_dealreview;
use Exception;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_cities(Request $request){
        try{

        $city=City::where('country_id',$request->country_id)->get();
        return response()->json([ 
            'result'=> true,
            'city'=>$city
        ]);
        }
        catch(Exception $ex)
              {
                  return $ex->getMessage();
              }  
    }
    public function get_userCity(Request $request){
        try{

            $user=Front_user::where('id',$request->user_id)->get();
            $countryName =Countrie::where('id',$user[0]->country_id)->get();
            $cityName =City::where('id',$user[0]->city_id)->get();
            $cityName_en= $cityName[0]->title_en;
            $cityName_ar= $cityName[0]->title_ar;

            $countryName_en= $countryName[0]->title_en;
            $countryName_ar= $countryName[0]->title_ar;
            $user_location=array("cityName_en"=>$cityName_en,"countryName_en"=>$countryName_en,"countryName_ar"=>$countryName_ar,"cityName_ar"=>$cityName_ar);
            return response()->json([ 
                'user_location'=> [$user_location],
            ]);
            }
            catch(Exception $ex)
            {
                return $ex->getMessage();
            } 
    }

    
}
