<?php

namespace App\Http\Controllers;

use App\Models\Front_user;
use App\Models\Item;
use App\Models\Item_favorite;
use App\Models\Item_image;
use App\Models\Item_report;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;

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


    public function updatePostOffer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|int',
                'itemtype' => 'required|in:PRODUCT,SERVICE',
                'title' => 'required|string|max:65535',
                'description' => 'required|string|max:65535',
                'itemsectioncategoryid' => 'required|integer',
                'itemsectionsubcategoryid' => 'required|integer',
                'mbu' => 'required|integer',
                'unit' => 'required|in:PER VISIT,PER SESSION,PER HOUR,PER UNIT,PER PACKAGE',
                'country_id' => 'required|integer',
                'city_id' => 'required|integer',
                'preferred_item' => 'nullable|string|max:65535',
                'item_status' => 'required|in:Unavailable,Available'

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $PostOffer = Item::where('id', $request->id)->where('offerdemandswap', 'OFFERED')->update([
                'itemtype' => $request->itemtype,
                'title' => $request->title,
                'description' => $request->description,
                'itemsectioncategoryid' => $request->itemsectioncategoryid,
                'itemsectionsubcategoryid' => $request->itemsectionsubcategoryid,
                'mbu' => $request->mbu,
                'unit' => $request->unit,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'preferred_item' => $request->preferred_item,
                'item_status' => $request->item_status
            ]);
            // THIS ENDPOINT STILL NEED IMG UPDATE

            if ($PostOffer) {
                return response()->json([
                    'result' => true,
                    'message' => 'Updated Successfully'
                ]);
            } else {
                return response()->json([
                    'result' => false
                ]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function updateDemandOffer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|int',
                'itemtype' => 'required|in:PRODUCT,SERVICE',
                'title' => 'required|string|max:65535',
                'description' => 'required|string|max:65535',
                'itemsectioncategoryid' => 'required|integer',
                'itemsectionsubcategoryid' => 'required|integer',
                'country_id' => 'required|integer',
                'city_id' => 'required|integer',
                'preferred_item' => 'nullable|string|max:65535',
                'item_status' => 'required|in:Unavailable,Available'

            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            } else {
                $demandOffer = Item::where('id', $request->id)->where('offerdemandswap', 'DEMAND')->update([
                    'itemtype' => $request->itemtype,
                    'title' => $request->title,
                    'description' => $request->description,
                    'itemsectioncategoryid' => $request->itemsectioncategoryid,
                    'itemsectionsubcategoryid' => $request->itemsectionsubcategoryid,
                    'country_id' => $request->country_id,
                    'city_id' => $request->city_id,
                    'preferred_item' => $request->preferred_item,
                    'item_status' => $request->item_status
                ]);
                // THIS ENDPOINT STILL NEED IMG UPDATE





                if ($demandOffer) {
                    return response()->json([
                        'result' => true,
                        'message' => 'Updated Successfully'
                    ]);
                } else {
                    return response()->json([
                        'result' => false
                    ]);
                }
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    // }


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

        if ($request->user_id) { //returns offers of a queried User

            $offers = Item::where('offerdemandswap', 'OFFERED')
                ->where('item_status', 'Available')
                ->where('frontuser_id', $request->user_id)
                ->where('status', 'ACTIVE')
                ->get();  //List of offers that are: Offered, Active, available and belong to the current queried user
            return response()->json([
                'result' => true,
                'offers' => $offers
            ]); //JSON array name

        } else {

            $offers = Item::where('offerdemandswap', 'OFFERED')
                ->where('item_status', 'Available')
                ->where('status', 'ACTIVE')
                ->get();  //Lists ALL offers that are: Offered, Active and available
            return response()->json([
                'result' => true,
                'offers' => $offers //JSON array name
            ]);
        }
        if ($request->search) { //returns search results when there's a search key (still not sure which fields should i return yet)

            // $searchKey = $request->search;
            // Item::Where('', 'like', '%' . $searchKey . '%')
            //         ->orWhere('', 'like', '%' . $searchKey . '%')
            //         ->orWhere('', 'like', '%' . $searchKey . '%')
            //         ->orWhere('', 'like', '%' . $searchKey . '%')->get();


        }
    }
    public function get_cat_offers(Request $request)
    { //params: cat,sub_cat,last_id
        // get offers from a certain category / subcategory

        //category table is empty (where are the categories stored?)


    }
    public function get_demand(Request $request)
    { //params: last_id,search,user_id
        //get demands based on provider variables


        if ($request->user_id) { //returns DEMAND of a queried User

            $demands = Item::where('offerdemandswap', 'DEMAND')
                ->where('item_status', 'Available')
                ->where('frontuser_id', $request->user_id)
                ->where('status', 'ACTIVE')
                ->get();  //List of DEMANDs that are: DEMAND, Active, available and belong to the current queried user
            return response()->json([
                'result' => true,
                'demands' => $demands
            ]); //JSON array name

        } else {

            $demands = Item::where('offerdemandswap', 'DEMAND')
                ->where('item_status', 'Available')
                ->where('status', 'ACTIVE')
                ->get();  //Lists ALL demands that are: DEMAND, Active and available
            return response()->json([
                'result' => true,
                'demands' => $demands //JSON array name
            ]);
        }
    }
    public function get_cat_demand(Request $request)
    { //params: cat,sub_cat,last_id
        // get demands from a certain category
        //category table is empty (where are the categories stored?)
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
    // public function post_demand(Request $request)
    // { //params: post_type,title,des,prefer,cat,subcat,country,city,user_id,images
    //     // inserting a demand using the data provided

    // }

    public function add_like(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|integer',
                'frontuser_id' => 'required|integer',
            ]);
                
            if ($validator->fails()) {
                // Validation failed
                return response()->json([
                    'error' => $validator->errors(),
                ], 422);
            }
            else{
                $item = Item_favorite::create([
                    'item_id' => $request->item_id,
                    'frontuser_id' => $request->frontuser_id,
                ]);
                
                if ($item) {
                    return response()->json([
                        'result'=>true,
                        'message'=>'Added Successfully',
                        
                    ]);
                  } else {
                    return response()->json([
                        'result'=>false,
                        'message'=>'Added faild',
                        
                    ]);
                  }
                
            }
        } catch (Exception $ex) {
           return $ex->getMessage();
        }
    }


    // start endpoint post_offer
    public function post_offer(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'itemtype' => 'required|string',
                'frontuser_id' => 'required|integer',
                'title' => 'required|string',
                'description' => 'required|string',
                'itemsectioncategoryid' => 'required|integer',
                'itemsectionsubcategoryid' => 'required|integer',
                'offerdemandswap' => 'required|string',
                'mbu' => 'required|integer',
                'unit' => 'required|string',
                'country_id' => 'required|integer',
                'city_id' => 'required|integer',
                'image' => 'string',
                'preferred_item' => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }else{
            $Items=Item::create([
                'itemtype'=>$request->itemtype,
                'frontuser_id'=>$request->frontuser_id,
                'title'=>$request->title,
                'description'=>$request->description,
                'itemsectioncategoryid'=>$request->itemsectioncategoryid,
                'itemsectionsubcategoryid'=>$request->itemsectionsubcategoryid,
                'offerdemandswap'=>$request->offerdemandswap,
                'mbu'=>$request->mbu,
                'unit'=>$request->unit,
                'country_id'=>$request->country_id,
                'city_id'=>$request->city_id,
                'preferred_item'=>$request->preferred_item,
                'quantity'=>1,
                'totalview'=>0
            ]);
            $lastItem = Item::latest()->first();
            $lastId = $lastItem->id;
            if($request->image) {
    			$images = json_decode($request->image, true);
                foreach ($images as $image){
                    $imagePath= 'images/'.$image;
                    $imagenew = Image::make($imagePath);
                    $ResizeImage= $imagenew->resize(720, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $ResizeImage->save('images/items_864_636.png');
                    $itemImage = new Item_image();
                    $itemImage->item_id = $lastId;
                    $itemImage->name = $image;
                    $itemImage->save();
                }
    		}
    		}
            $permissionid=Permission::where('product_status',1)->get('id');
            $idarray=array();
            foreach ($permissionid as $id){
                array_push($idarray,$id->id );
            }
            $useremail=User::whereIn('permissions_id',$idarray)->where('status',1)->get('email');
            $emailarray=array();
            foreach ($useremail as $email){
                array_push($emailarray,$email->email );
            }
            $data = ['message' => 'Product added need to be reviewed.'];
            // Mail::send('mail@tabdeal.online', $data, function($message)
            // {
            //     $permissionid=Permission::where('product_status',1)->get('id');
            //     $idarray=array();
            //     foreach ($permissionid as $id){
            //         array_push($idarray,$id->id );
            //     }
            //     $useremail=User::whereIn('permissions_id',$idarray)->where('status',1)->get('email');
            //     foreach ($useremail as $email){
            //     $message->to($email->email , '')
            //             ->subject('Tabdeal Product added');
            //         }
            //     });
            if($Items){
                return response()->json([ 
                    'result'=> true,
                    'sent'=> true,
                    'message'=>'Added Successfully'
                ]);
            }else {
                return response()->json([ 
                    'result'=> false,
                ]);
            }
        }
        catch(Exception $ex)
                {
                    return $ex->getMessage();
                }  
    }
    // end endpoint post_offer

     // start endpoint post_demand
     public function post_demand(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'itemtype' => 'required|string',
                'frontuser_id' => 'required|integer',
                'title' => 'required|string',
                'description' => 'required|string',
                'itemsectioncategoryid' => 'required|integer',
                'itemsectionsubcategoryid' => 'required|integer',
                'country_id' => 'required|integer',
                'city_id' => 'required|integer',
                'image' => 'string',
                'preferred_item' => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'errors' => $validator->errors()
                ], 422);
            }else{
            $Items=Item::create([
                'itemtype'=>$request->itemtype,
                'frontuser_id'=>$request->frontuser_id,
                'title'=>$request->title,
                'description'=>$request->description,
                'itemsectioncategoryid'=>$request->itemsectioncategoryid,
                'itemsectionsubcategoryid'=>$request->itemsectionsubcategoryid,
                'country_id'=>$request->country_id,
                'city_id'=>$request->city_id,
                'preferred_item'=>$request->preferred_item,
                'quantity'=>1,
                'totalview'=>0
            ]);
            $lastItem = Item::latest()->first();
            $lastId = $lastItem->id;
            if($request->image) {
    			$images = json_decode($request->image, true);
                foreach ($images as $image){
                    $imagePath= 'images/'.$image;
                    $imagenew = Image::make($imagePath);
                    $ResizeImage= $imagenew->resize(720, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $ResizeImage->save('images/items_864_636.png');
                    $itemImage = new Item_image();
                    $itemImage->item_id = $lastId;
                    $itemImage->name = $image;
                    $itemImage->save();
                }
    		}
    		}
            $permissionid=Permission::where('product_status',1)->get('id');
            $idarray=array();
            foreach ($permissionid as $id){
                array_push($idarray,$id->id );
            }
            $useremail=User::whereIn('permissions_id',$idarray)->where('status',1)->get('email');
            $emailarray=array();
            foreach ($useremail as $email){
                array_push($emailarray,$email->email );
            }
            $data = ['message' => 'Product added need to be reviewed.'];
            // Mail::send('mail@tabdeal.online', $data, function($message)
            // {
            //     $permissionid=Permission::where('product_status',1)->get('id');
            //     $idarray=array();
            //     foreach ($permissionid as $id){
            //         array_push($idarray,$id->id );
            //     }
            //     $useremail=User::whereIn('permissions_id',$idarray)->where('status',1)->get('email');
            //     foreach ($useremail as $email){
            //     $message->to($email->email , '')
            //             ->subject('Tabdeal Product added');
            //         }
            //     });
            if($Items){
                return response()->json([ 
                    'result'=> true,
                    'sent'=> true,
                    'message'=>'Added Successfully'
                ]);
            }else {
                return response()->json([ 
                    'result'=> false,
                ]);
            }
        }
        catch(Exception $ex)
                {
                    return $ex->getMessage();
                }  
    }
    // end endpoint post_demand

}
