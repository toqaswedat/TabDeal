<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Business_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class BusinessController extends Controller
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
    public function getAllBusinesses(Request $request)
    {
        try {

            $businesses = Business::where('iFrontUsersId', $request->iFrontUsersId)->get();
            return response()->json([
                'user' => $businesses[0]
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function get_business_categories()
    {
        try {
            $business_categories = Business_category::orderBy('id', 'desc')->get();
            return response()->json([
                'data' => $business_categories
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
