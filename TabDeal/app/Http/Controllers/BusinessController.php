<?php

namespace App\Http\Controllers;
    
use App\Models\Business;
use App\Models\Business_category;
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


    public function updateBusinessProfile(Request $request){
        try{
            $validator = validator::make($request->all(),[
                'vName' => 'required|string|max:255',
                'vVatNumber' => 'required|integer',
                'vBusinessAddress' => 'required|string|max:1000',
                'vBusinessAddress2' => 'nullable|string|max:1000',
                'vWebsiteAddress' => 'nullable|url',
                'iBusinessCategoryId' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);}else{ 
                    
                    $businesUpdate = Business::where('id', $request->id)->update([
                    'vName' => $request->vName,
                    'vVatNumber' => $request->vVatNumber,
                    'vBusinessAddress' => $request->vBusinessAddress,
                    'vBusinessAddress2' => $request->vBusinessAddress2,
                    'vWebsiteAddress' => $request->vWebsiteAddress,
                    'iBusinessCategoryId' => $request->iBusinessCategoryId
                    //THIS END POINT STILL NEED IMG UPDATE
                ]);
                if ($businesUpdate) {
                    return response()->json([
                        'result' => true,
                        'message' => 'Updated Successfully'
                    ]);
                } else {
                    return response()->json([
                        'result' => false,
                    ]);
                }}


            } catch (Exception $ex) {
                return $ex->getMessage();
            }}

        
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
