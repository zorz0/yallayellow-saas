<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Customer as NewCustomer;
use App\Models\Utility;
use App\Models\WoocommerceConection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Codexshaper\WooCommerce\Facades\Customer;

class WoocomCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Woocommerce Customer'))
        {
            try{
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
                $woocommerce_store_url = Utility::GetValueByName('woocommerce_store_url',$theme_name);
                $woocommerce_consumer_secret = Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
                $woocommerce_consumer_key = Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

                config(['woocommerce.store_url' => $woocommerce_store_url]);
                config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
                config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

                $jsonData = Customer::all(['per_page' => 100]);
                $upddata = WoocommerceConection::where('theme_id',$theme_name)->where('store_id',getCurrentStore())->where('module','=','customer')->get()->pluck('woocomerce_id')->toArray();
                return view('woocommerce.customer', compact('jsonData','upddata'));
            }
            catch(\Exception $e){
                return redirect()->back()->with('error' , 'Something went wrong.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
           //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$id)
    {
         
        // if(auth()->user()->isAbleTo('Create Woocommerce Customer'))
        // {
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

            $jsonData = Customer::find($id);

            if(!empty($jsonData['avatar_url'])) {

                $url = $jsonData['avatar_url'];
                $file_type = config('files_types');

                $name ='avtar.png';
                $file2 = rand(10,100).'_'.time() . "_" . $name;
                $path = 'themes/'.APP_THEME().'/uploads/customerprofile/';
                $uplaod =Utility::upload_woo_file($url,$file2,$path);
            }
            try {

                $cutomer                = new NewCustomer();
                $cutomer->first_name    = $jsonData['first_name'];
                $cutomer->last_name     = $jsonData['last_name'];
                $cutomer->profile_image = $uplaod['url'];
                $cutomer->email         =$jsonData['email'];
                $cutomer->type          ='customer';
                $cutomer->password      = Hash::make('1234');
                $cutomer->register_type = 'email';
                $cutomer->mobile        = !empty($jsonData['billing']) ? $jsonData['billing']->phone : $jsonData['shipping']->phone;
                $cutomer->theme_id      = APP_THEME();
                $cutomer->store_id      = getCurrentStore();
                $cutomer->created_by    = auth()->user()->id;
                $cutomer->save();

                $Cutomer                    = new WoocommerceConection();
                $Cutomer->store_id          = getCurrentStore();
                $Cutomer->theme_id          = APP_THEME();
                $Cutomer->module            = 'customer';
                $Cutomer->woocomerce_id     = $jsonData['id'];
                $Cutomer->original_id       = $cutomer->id;
                $Cutomer->save();
                $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            

                return redirect()->back()->with('success', __('Customer successfully Add , We set customer password 1234'));
            }
            catch(\Exception $e){
                    return redirect()->back()->with('success', __('This email alredy use.'));
            }
            return redirect()->back()->with('success', __('Customer successfully Add.'));

        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         
        // if(auth()->user()->isAbleTo('Edit Woocommerce Customer'))
        // {
            $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
            $woocommerce_store_url =Utility::GetValueByName('woocommerce_store_url',$theme_name);
            $woocommerce_consumer_secret =Utility::GetValueByName('woocommerce_consumer_secret',$theme_name);
            $woocommerce_consumer_key =Utility::GetValueByName('woocommerce_consumer_key',$theme_name);

            config(['woocommerce.store_url' => $woocommerce_store_url]);
            config(['woocommerce.consumer_key' => $woocommerce_consumer_key]);
            config(['woocommerce.consumer_secret' => $woocommerce_consumer_secret]);

            $jsonData = Customer::find($id);

            try{

                $store_id = Store::where('id', getCurrentStore())->first();
                $upddata = WoocommerceConection::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->where('module','=','customer')->where('woocomerce_id' , $id)->first();
                $original_id = $upddata->original_id;
                $cutomer = NewCustomer::find($original_id);

                if(!empty($jsonData['avatar_url'])) {
                    $url = $jsonData['avatar_url'];
                    $file_type = config('files_types');

                    $name ='avtar.png';
                    $file2 = rand(10,100).'_'.time() . "_" . $name;
                    $path = 'themes/'.APP_THEME().'/uploads/customerprofile/';
                    $uplaod =Utility::upload_woo_file($url,$file2,$path);
                }
                if (!empty($jsonData)) {
                    $cutomer->first_name    = $jsonData['first_name'];
                    $cutomer->last_name     = $jsonData['last_name'];
                    $cutomer->profile_image = $uplaod['url'];
                    $cutomer->email         =$jsonData['email'];
                    $cutomer->type          ='customer';
                    $cutomer->register_type = 'email';
                    $cutomer->mobile        = !empty($jsonData['billing']) ? $jsonData['billing']->phone : $jsonData['shipping']->phone;
                    $cutomer->theme_id      = APP_THEME();
                    $cutomer->store_id      = getCurrentStore();
                    $cutomer->created_by    = auth()->user()->id;
                    $cutomer->save();

                    return redirect()->back()->with('success', __('Customer successfully updated.'));

                } else {
                    return redirect()->back()->with('error', __('Customer Not Found.'));
                }
            }
            catch(\Exception $e){
                return redirect()->back()->with('error', __('This email already used.'));

            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
