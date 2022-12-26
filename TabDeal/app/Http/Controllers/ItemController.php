<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
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
    public function get_single_post(Request $request)
    {
        try {

            $Post = Item_report::where('userid_reported', $request->userid_reported)->where('item_id', $request->item_id)->get();

            return response()->json([
                'result' => true,
                'reports' => $Post
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
    public function get_user_fav_items(Request $request)
    {
        try {
            $profile_url = 'https://tabdeal.online/';
            $favItems = Item_favorite::where('frontuser_id', $request->frontuser_id)->get();
            $IteemArray = array();
            $imagesArray = array();
            foreach ($favItems as $favItem) {
                $Item = Item::where('id', $favItem->item_id)->get();
                $FrontUser = Front_user::where('id', $Item[0]->frontuser_id)->get();
                $Images = Item_image::where('item_id', $Item[0]->id)->get();
                $userArrayReviews = array();
                foreach ($Images as $Image) {
                    $imagesArray1 = $Image->name;
                    array_push($imagesArray, $imagesArray1);
                }
                $time = strtotime($Item[0]->created_at);
                $newformat = date('Y-m-d', $time);
                $time1 = strtotime($Item[0]->updated_at);
                $newformat1 = date('Y-m-d h:m:s', $time1);
                $time2 = strtotime($FrontUser[0]->created_at);
                $newformat2 = date('d M, Y', $time2);
                $userArrayReviews["id"] = $Item[0]->id;
                $userArrayReviews["itemsectioncategoryid"] = $Item[0]->itemsectioncategoryid;
                $userArrayReviews["itemsectionsubcategoryid"] = $Item[0]->itemsectionsubcategoryid;
                $userArrayReviews["frontuser_id"] = $Item[0]->frontuser_id;
                $userArrayReviews["title"] = $Item[0]->title;
                $userArrayReviews["description"] = $Item[0]->description;
                $userArrayReviews["preferred_item"] = $Item[0]->preferred_item;
                $userArrayReviews["itemtype"] = $Item[0]->itemtype;
                $userArrayReviews["offerdemandswap"] = $Item[0]->offerdemandswap;
                $userArrayReviews["mbu"] = $Item[0]->mbu;
                $userArrayReviews["quantity"] = $Item[0]->quantity;
                $userArrayReviews["unit"] = $Item[0]->unit;
                $userArrayReviews["itemtags"] = $Item[0]->itemtags;
                $userArrayReviews["country_id"] = $Item[0]->country_id;
                $userArrayReviews["state_id"] = $Item[0]->state_id;
                $userArrayReviews["city_id"] = $Item[0]->city_id;
                $userArrayReviews["status"] = $Item[0]->status;
                $userArrayReviews["item_status"] = $Item[0]->item_status;
                $userArrayReviews["statusupdatetime"] = $Item[0]->statusupdatetime;
                $userArrayReviews["poststatusupdate"] = $Item[0]->poststatusupdate;
                $userArrayReviews["totalview"] = $Item[0]->totalview;
                $userArrayReviews["created_at"] = $newformat;
                $userArrayReviews["updated_at"] = $newformat1;
                $userArrayReviews["username"] = $FrontUser[0]->vFirstName . " " . $FrontUser[0]->vLastName;
                $userArrayReviews["user_image"] = $profile_url . 'api/uploads/users/' . $FrontUser[0]->vProfilePic;
                $userArrayReviews["join_time"] = $newformat2;
                $userArrayReviews["acc_type"] = $FrontUser[0]->eMemberType;
                $userArrayReviews["tp"] = $Item[0]->mbu;
                $userArrayReviews["post_type"] = strtolower($Item[0]->itemtype);
                $userArrayReviews["thumb"] = $profile_url . 'api/uploads/items/' . $imagesArray[0];
                $userArrayReviews["image"] = $imagesArray;
                $imagesArray = array();
                array_push($IteemArray, $userArrayReviews);
            }
            return response()->json([
                'result' => true,
                'fav' => $IteemArray
            ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function deleteData(Request $request)
    {
        try {
            $item = Item::where('id', $request->id)->first();
            if ($item != null) {
                $item->delete();
                return response()->json([
                    'result' => true,
                    'message' => 'Deleted Successfully'
                ]);
            } else
                return response()->json([
                    'result' => false
                ]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }



    /*
* The lines below are the functions responsible for Item CRUD operations
* 1.get_offers
* 2.get_cat_offers
* 3.get_demand
* 4.get_cat_demand
* 5.search (no need for search_load_more since we use withPagination in laravel)
* 6.update_post_offer
* 7.update_demand_offer
* 8.post_offer
* 7.post_demand
* 8.delete_demand
* 9.delete_offer
 */


    public function get_offers(Request $request)
    { //params: last_id,search,frontuser_id
        // get all offers based on data provided
        
        $offers = Item::where('offerdemandswap', 'OFFERED')
            ->where('item_status', 'Available')
            ->where('status', 'ACTIVE')
            ->get();  //List of offers that are: Offered, Active and available
            


        return response()->json([
            'result' => true,
            'offers' => $offers //JSON array name
        ]);
    }
    public function get_cat_offers(Request $request)
    { //params: cat,sub_cat,last_id
        // get offers from a certain category / subcategory
    }
    public function get_demand(Request $request)
    { //params: last_id,search,user_id
        //get demands based on provider variables
    }
    public function get_cat_demand(Request $request)
    { //params: cat,sub_cat,last_id
        // get demands from a certain category
    }
    public function search(Request $request)
    { //params: itype,search
        // search item via type or by using a keyword
    }
    public function update_post_offer(Request $request)
    { //params: post_type,title,des,prefer,cat,subcat,tp,unit,country,city,user_id,images,id,item_status
        // updating an existing offer using the data provided
    }
    public function update_demand_offer(Request $request)
    { //params: post_type,title,des,prefer,cat,subcat,country,city,user_id,images,post_id,item_status
        // updating an existing demand using the data provided
    }
    public function post_offer(Request $request)
    { //params: post_type,title,des,prefer,cat,subcat,tp,unit,country,city,user_id,images
        // inserting  an offer using the data provided
    }
    public function post_demand(Request $request)
    { //params: post_type,title,des,prefer,cat,subcat,country,city,user_id,images
        // inserting a demand using the data provided
    }
}
