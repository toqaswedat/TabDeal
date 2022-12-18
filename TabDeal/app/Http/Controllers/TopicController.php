<?php

namespace App\Http\Controllers;

use App\Models\Topics;
use Illuminate\Http\Request;
use Exception;
use DateTimeInterface;
class TopicController extends Controller
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
    public function getTopicsByUrlHiw()
    {
        try {

            $topics = Topics::where('content_url', 'how-it-works_howitworks')->get();
            return response()->json([
                'result' => true,
                'faq_hiw' => $topics
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }


    public function getTopicsByUrlVideos()
    {
        try {

            $topics = Topics::where('content_url', 'how-it-works_videos')->get();
            return response()->json([
                'result' => true,
                'hiw_videos' => $topics
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    
    public function getTopicsByFaq()
    {
        try {

            $topics = Topics::where('content_url', 'how-it-works_faqs')->get();
            return response()->json([
                'result' => true,
                'faq_data' => $topics
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}