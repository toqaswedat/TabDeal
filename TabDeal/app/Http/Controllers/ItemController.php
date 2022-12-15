<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Item_favorite;
use App\Models\Item_image;
use App\Models\Item_report;
use Illuminate\Http\Request;
use Exception;

class ItemController extends Controller
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
    public function getItemById(Request $request)
    {
        try {

            $items = Item::where('id', $request->id)->get();
            return response()->json([
                'result' => true,
                'data' => $items
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function get_single_post(Request $request){
        try{

            $Post=Item_report::where('userid_reported',$request->userid_reported)->where('item_id',$request->item_id)->get();

            return response()->json([ 
                'result'=> true,
                'reports'=>$Post
            ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    
    
}
