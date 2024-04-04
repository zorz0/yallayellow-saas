<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\MainCategory;
use App\Models\shopifyconection;
use App\Models\Theme;
use App\Models\Utility;
use App\Models\woocommerceconection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Product Category'))
        {

            $MainCategory = MainCategory::where('theme_id',APP_THEME())->where('store_id',getCurrentStore())->get();
            return view('maincategory.index', compact('MainCategory'));
        }else{
            return redirect()->back()->with('error',__('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $theme_list = Theme::ThemeList();
        return view('maincategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->isAbleTo('Create Product Category'))
        {
            $store_id = Store::where('id', getCurrentStore())->first();

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'image' => 'required',
                                   'icon_image' => 'required',
                                   'trending' => 'required',
                                   'status' => 'required',
                                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            $dir        = 'themes/'.APP_THEME().'/uploads';
            if($request->image) {
                $image_size = $request->file('image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1)
                {
                    $fileName = rand(10,100).'_'.time() . "_" . $request->image->getClientOriginalName();
                    $path = Utility::upload_file($request,'image',$fileName,$dir,[]);
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

            if($request->icon_image) {
                $image_size = $request->file('icon_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1)
                {
                    $fileName = rand(10,100).'_'.time() . "_" . $request->icon_image->getClientOriginalName();
                    $paths = Utility::upload_file($request,'icon_image',$fileName,$dir,[]);
                    if ($paths['flag'] == 1) {
                        $url = $paths['url'];
                    } else {
                        return redirect()->back()->with('error', __($paths['msg']));
                    }
                }
                else{
                    return redirect()->back()->with('error', $result);
                }
            }

            // dd(\Storage::disk('wasabi')->url($path['url']) , \Storage::disk('wasabi')->url($paths['url']));

            $MainCategory = new MainCategory();
            $MainCategory->name         = $request->name;
            $MainCategory->slug             =  'collections/' . strtolower(preg_replace("/[^\w]+/", "-", $request->name));
            $MainCategory->image_url    = $path['full_url'];
            $MainCategory->image_path   = $path['url'];
            $MainCategory->icon_path    = $paths['url'];
            $MainCategory->trending     = $request->trending;
            $MainCategory->status       = $request->status;
            $MainCategory->theme_id     = APP_THEME();
            $MainCategory->store_id     = getCurrentStore();

            $MainCategory->save();

            return redirect()->back()->with('success', __('Category successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MainCategory $mainCategory)
    {
        return view('maincategory.edit', compact('mainCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MainCategory $mainCategory)
    {
        
        // if(auth()->user()->isAbleTo('Edit Product Category'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'status' => 'required',
                                   'trending' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $dir        = 'themes/'.APP_THEME().'/uploads';

            $MainCategory = $mainCategory;
            $MainCategory->name = $request->name;


            if(!empty($request->image)) {
                $file_path =  $mainCategory->image_path;
                $image_size = $request->file('image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1)
                {
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                    $fileName = rand(10,100).'_'.time() . "_" . $request->image->getClientOriginalName();
                    $path = Utility::upload_file($request,'image',$fileName,$dir,[]);
                    if ($path['flag'] == 1) {
                        $MainCategory->image_url    = $path['full_url'];
                        $MainCategory->image_path   = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                else{
                    return redirect()->back()->with('error', $result);
                }
            }

            if(!empty($request->icon_image)) {
                $file_path =  $mainCategory->icon_path;
                $image_size = $request->file('icon_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if ($result == 1)
                {
                    Utility::changeStorageLimit(\Auth::user()->creatorId(), $file_path);

                    $fileName = rand(10,100).'_'.time() . "_" . $request->icon_image->getClientOriginalName();
                    $paths = Utility::upload_file($request,'icon_image',$fileName,$dir,[]);
                    if ($paths['flag'] == 1) {
                        $MainCategory->icon_path    = $paths['url'];
                    } else {
                        return redirect()->back()->with('error', __($paths['msg']));
                    }
                }
                else{
                    return redirect()->back()->with('error', $result);
                }

            }
            $MainCategory->slug         =  'collections/' . strtolower(preg_replace("/[^\w]+/", "-", $request->name));
            $MainCategory->trending     = $request->trending;
            $MainCategory->status       = $request->status;
            $MainCategory->save();

            return redirect()->back()->with('success', __('Category successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainCategory $mainCategory)
    {
        
        // if(auth()->user()->isAbleTo('Delete Product Category'))
        // {
            $category = $mainCategory;

            $file_path1[] =  $mainCategory->image_path;
            $file_path2[] =  $mainCategory->icon_path;
            $file_path    =   array_merge($file_path1 ,$file_path2);


            Utility::changeproductStorageLimit(\Auth::user()->creatorId(), $file_path );

            if(!empty($category)) {

                // woocommerceconection::where('original_id', $category->id)->delete();

                // shopifyconection::where('original_id', $category->id)->delete();

                $category->delete();


            }
            return redirect()->back()->with('success', __('Category delete successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function getProductCategories()
    {
        $store_id = Store::where('id', getCurrentStore())->first();
        // $user = \Auth::user()->current_store;
        $productCategory = MainCategory::where('theme_id',$store_id->theme_id)->where('store_id',getCurrentStore())->get();
        $html = '<div class="mb-3 mr-2 mx-2 zoom-in ">
                    <div class="card rounded-10 card-stats mb-0 cat-active overflow-hidden" data-id="0">
                    <div class="category-select" data-cat-id="0">
                        <button type="button" class="btn tab-btns btn-primary">'.__("All Categories").'</button>
                    </div>
                    </div>
                </div>';
        foreach($productCategory as $key => $cat){
            $dcls = 'category-select';
            $html .= ' <div class="mb-3 mr-2 mx-2 zoom-in cat-list-btn">
            <div class="card rounded-10 card-stats mb-0 overflow-hidden " data-id="'.$cat->id.'">
               <div class="'.$dcls.'" data-cat-id="'.$cat->id.'">
                  <button type="button" class="btn tab-btns ">'.$cat->name.'</button>
               </div>
            </div>
         </div>';

        }
        return Response($html);
    }
}
