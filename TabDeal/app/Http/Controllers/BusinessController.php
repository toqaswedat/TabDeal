<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
}
