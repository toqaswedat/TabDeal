<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Topic_categorie;
use App\Models\Topics;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionsController extends Controller
{
    //
    public function get_categories(){
        try{
        $profile_url = 'https://tabdeal.online/';
        $webmasters=Section::where('webmaster_id',11)->orderBy('title_en', 'ASC')->get();
        foreach ($webmasters as $webmaster) {
        $webmaster->photo = $profile_url."old_website/uploads/sections/".$webmaster->photo;
        $sub=Topic_categorie::where('section_id',$webmaster->id)->get(['topic_id','section_id']);
        foreach ($sub as $subTopicId) {
        $subTopicId->title_ar=Topics::where('id',$subTopicId->topic_id)->get(['title_ar','title_en']);
        }
        $webmaster->sub=$sub;
        }
        return response()->json([ 
            'result'=> true,
            'category'=>$webmasters
        ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }

}
