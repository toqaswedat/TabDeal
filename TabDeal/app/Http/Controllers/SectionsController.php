<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Item;
use App\Models\Section;
use App\Models\Topic_categorie;
use App\Models\Topics;
use Exception;
use GuzzleHttp\Psr7\Request;

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
        $subTopicId->title=Topics::where('id',$subTopicId->topic_id)->get(['title_ar','title_en']);
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

    public function categories_offers(){
        try{
            $categories=Section::all();
            $arrayCount=array();
            foreach ($categories as $category) {
                $ItemOffered=Item::where('offerdemandswap','OFFERED')->where('itemsectioncategoryid',$category->id)->get();
                $arrayCount[$category->id]=count($ItemOffered);
            }
            return response()->json([ 
                'result'=> true,
                'categories_offers'=>$arrayCount
            ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }
    public function offers_num(){
        try{
            $ItemOffered=Item::where('offerdemandswap','OFFERED')->get();
            $arrayCount=count($ItemOffered);
        return response()->json([ 
            'result'=> true,
            'offers_num'=>$arrayCount
        ]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }  
    }

}
