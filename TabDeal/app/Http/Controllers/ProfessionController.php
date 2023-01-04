<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Exception;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function get_profession(Request $request)
    {
        try {
            $professions = Profession::all();
            return response()->json([
                'data' => $professions
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}
