<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class user extends Controller
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
    public function index(){
        $users = DB::table('front_users')->select(['eMemberType','vFirstName'])->whereNotNull('eMemberType')->orderBy('eMemberType')->get();
        dd($users);
    } 
}
