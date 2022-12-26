<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddDataController extends Controller
{
    //
    public function add_data(Request $request){
        try{
        $fields = "";
        $data1 = "";
        $table_name=$request->table_name;
		if($table_name == 'requests') {
			$request->order_id = random_int(10000000,99999999);
		}
        $idTable=DB::select("SELECT id from `{$table_name}` order by id desc limit 1" )[0];
        $id=$idTable->id+1;
        $image = $request->image;
        unset($request['image']);
        if($table_name == 'deals') {
    		$msg = $request->message;
            unset($request['message']);
		}
		$numItemsToArray = $request->toArray() ;
        $numItems=count($numItemsToArray)-2;
		$i = 0;
        foreach($request as $Key=>$value) {
            if($Key != "type" && $Key != "table_name") {
                if($value == '')
                  $value = NULL;
                if(++$i == $numItems) {
                    $fields .= "`$Key`";
                    $data1 .= "'$value'";
                  } else {
                    $fields .= "`$Key`, ";
                    $data1 .= "'$value', ";
                    }
                }
            }
        return $request;
        return response()->json([ 
            'result'=> true,

        ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
}
