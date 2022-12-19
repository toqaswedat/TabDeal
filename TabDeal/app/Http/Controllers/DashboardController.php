<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
use App\Models\Item;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
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
    public function Dashboard(Request $request){
        try {
            // $Items = Front_user::where('id', $request->user_id)->get();

            $ItemsO = Item::where('frontuser_id', $request->user_id)->where('offerdemandswap', 'OFFERED')->get('id');
            $ItemsD = Item::where('frontuser_id', $request->user_id)->where('offerdemandswap', 'DEMAND')->get('id');
            return response()->json([
                'result' => true,
                'oferrs' => count($ItemsO),
                'demands' => count($ItemsD)
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

    }
}
