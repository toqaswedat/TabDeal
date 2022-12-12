<?php

namespace App\Http\Controllers;

use App\Models\Citie;
use Exception;
use Illuminate\Http\Request;

class CitieController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_cities(Request $request){
        try{

        $city=Citie::where('country_id',$request->country_id)->get();
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
    public function __invoke(Request $request)
    {
        //
    }
}
