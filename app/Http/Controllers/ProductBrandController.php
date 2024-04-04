<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductBrand;
use App\Models\Utility;

class ProductBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (auth()->user()->isAbleTo('Manage Product Brand')) {
            $productBrands = ProductBrand::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();

            return view('product_brand.index',compact('productBrands'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product_brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (auth()->user()->isAbleTo('Create Product Brand')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'logo' => 'required'
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $slug = ProductBrand::slugs($request->name);

            $url = null;            
            if($request->logo) {
                $dir        = 'themes/'.APP_THEME().'/uploads';
                $image_size = $request->file('logo')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1)
                {
                    $fileName = rand(10,100).'_'.time() . "_" . $request->logo->getClientOriginalName();
                    $path = Utility::upload_file($request,'logo',$fileName,$dir,[]);
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                else{
                    return redirect()->back()->with('error', $result);
                }

                // dd(\Storage::disk('wasabi')->url($path['url']));
            }

            $productBrand                      = new ProductBrand();
            $productBrand->name                = $request->name;
            $productBrand->logo                = $url;
            $productBrand->slug                = $slug;
            $productBrand->status              = $request->status;
            $productBrand->is_popular          = $request->is_popular;
            $productBrand->theme_id            = APP_THEME();
            $productBrand->store_id            = getCurrentStore();
            $productBrand->created_by          = auth()->user()->id;
            $productBrand->save();
            return redirect()->back()->with('success', __('Product Brand successfully created.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductBrand $productBrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductBrand $productBrand)
    {
        return view('product_brand.edit', compact('productBrand'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductBrand $productBrand)
    {
        
        // if (auth()->user()->isAbleTo('Edit Product Brand')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $url = null;            
            if($request->logo) {
                $dir        = 'themes/'.APP_THEME().'/uploads';
                $image_size = $request->file('logo')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1)
                {
                    $fileName = rand(10,100).'_'.time() . "_" . $request->logo->getClientOriginalName();
                    $path = Utility::upload_file($request,'logo',$fileName,$dir,[]);
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                else{
                    return redirect()->back()->with('error', $result);
                }

                // dd(\Storage::disk('wasabi')->url($path['url']));
            }

            $productBrand->name                = $request->name;
            if ($url) {
                $productBrand->logo                = $url;
            }
            if ($request->status) {
                $productBrand->status              = $request->status;
            }
            if ($request->is_popular) {
                $productBrand->is_popular          = $request->is_popular;
            }
            $productBrand->theme_id            = APP_THEME();
            $productBrand->store_id            = getCurrentStore();
            $productBrand->save();

            return redirect()->back()->with('success', __('Product Brand successfully updated.'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductBrand $productBrand)
    {
        
        // if (auth()->user()->isAbleTo('Delete Product Brand')) {

            $productBrand->delete();
            return redirect()->back()->with('success', __('Product Brand delete successfully.'));

        // } else {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $productBrand = ProductBrand::find($request->id);
        
        if ($productBrand) {
            $productBrand->status = $request->status;
            $productBrand->save();
            $return['status'] = 'success';
            $return['message'] = __('Status change successfully.');
            return response()->json($return);
            // return success_res('Install successfully.');
        } else {
            $return['status'] = 'error';
            $return['message'] = __('Something went wrong!!');
            return response()->json($return);
        }
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function changePopular(Request $request)
    {
        $productBrand = ProductBrand::find($request->id);
       
        if ($productBrand) {
            $productBrand->is_popular = $request->is_popular;
            $productBrand->save();
            $return['status'] = 'success';
            $return['message'] = __('Status change successfully.');
            return response()->json($return);
            // return success_res('Install successfully.');
        } else {
            $return['status'] = 'error';
            $return['message'] = __('Something went wrong!!');
            return response()->json($return);
        }
    }
}
