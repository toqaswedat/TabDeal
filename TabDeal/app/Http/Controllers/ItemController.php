<?php

namespace App\Http\Controllers;

use App\Models\Item;
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
    
}
