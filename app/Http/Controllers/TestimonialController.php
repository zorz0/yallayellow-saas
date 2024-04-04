<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Testimonial'))
        {
            $testimonials = Testimonial::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();
            return view('testimonial.index', compact('testimonials'));
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
        $main_categorys = MainCategory::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->pluck('name', 'id')->prepend('Select Category', '');
        return view('testimonial.create', compact( 'main_categorys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->isAbleTo('Create Testimonial'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'maincategory_id' => 'required',
                    'subcategory_id' => 'required',
                    'product_id' => 'required',
                    'rating_no' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $Testimonial = new Testimonial();
            $Testimonial->maincategory_id = $request->maincategory_id;
            $Testimonial->subcategory_id = $request->subcategory_id;
            $Testimonial->product_id = $request->product_id;
            $Testimonial->rating_no = $request->rating_no;
            $Testimonial->title = $request->title;
            $Testimonial->description = $request->description;
            $Testimonial->status = $request->status;
            $Testimonial->theme_id = APP_THEME();
            $Testimonial->store_id = getCurrentStore();
            $Testimonial->save();

            // Testimonial::AvregeRating($request->product_id);

            return redirect()->back()->with('success', __('Testimonial create successfully.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Testimonial  $Testimonial
     * @return \Illuminate\Http\Response
     */
    public function show(Testimonial $Testimonial)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Testimonial  $Testimonial
     * @return \Illuminate\Http\Response
     */
    public function edit(Testimonial $Testimonial)
    {
        $main_categorys = MainCategory::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->pluck('name', 'id')->prepend('Select Category', '');
        $sub_categorys = SubCategory::where('theme_id', APP_THEME())->where('maincategory_id',$Testimonial->maincategory_id)->where('store_id',getCurrentStore())->pluck('name', 'id')->prepend('Select Subcategory', '');
        $product = Product::where('theme_id', APP_THEME())->where('subcategory_id',$Testimonial->subcategory_id)->where('store_id',getCurrentStore())->pluck('name', 'id')->prepend('Select Product', '');
        return view('testimonial.edit', compact( 'main_categorys','sub_categorys', 'Testimonial', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Testimonial  $Testimonial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimonial $Testimonial)
    {
        
        if (auth()->user()->isAbleTo('Edit Testimonial'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'maincategory_id' => 'required',
                    'subcategory_id' => 'required',
                    'product_id' => 'required',
                    'rating_no' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'status' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $Testimonial->maincategory_id = $request->maincategory_id;
            $Testimonial->subcategory_id = $request->subcategory_id;
            $Testimonial->product_id = $request->product_id;
            $Testimonial->rating_no = $request->rating_no;
            $Testimonial->title = $request->title;
            $Testimonial->description = $request->description;
            $Testimonial->status = $request->status;
            $Testimonial->theme_id = APP_THEME();
            $Testimonial->save();

            // Testimonial::AvregeRating($request->product_id);

            return redirect()->back()->with('success', __('Testimonial update successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Testimonial  $Testimonial
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimonial $Testimonial)
    {
        
        if (auth()->user()->isAbleTo('Delete Testimonial'))
        {
            $Testimonial->delete();
            return redirect()->back()->with('success', __('Testimonial delete successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function get_subcategory(Request $request)
    {
        $id = $request->id;
        $value = $request->val;
        $SubCategory = SubCategory::where('maincategory_id', $id)->get();
        $option = '<option value="">' . __('Select Product') . '</option>';
        foreach ($SubCategory as $key => $Category) {
            $select = $value == $Category->id ? 'selected' : '';
            $option .= '<option value="' . $Category->id . '" '.$select.'>' . $Category->name . '</option>';
        }

        $select =  '<select class="form-control" data-role="tagsinput" id="subcategory-dropdown" name="subcategory_id">'.$option.'</select>';
        $return['status'] = true;
        $return['html'] = $select;
        return response()->json($return);
    }
    public function get_product(Request $request)
    {
        $id = $request->id;
        $value = $request->val;
        $Product = Product::where('subcategory_id', $id)->get();
        $option = '<option value="">' . __('Select Product') . '</option>';
        foreach ($Product as $key => $Category) {
            $select = $value == $Category->id ? 'selected' : '';
            $option .= '<option value="' . $Category->id . '" '.$select.'>' . $Category->name . '</option>';
        }

        $select =  '<select class="form-control" data-role="tagsinput" id="product_id" name="product_id">'.$option.'</select>';
        $return['status'] = true;
        $return['html'] = $select;
        return response()->json($return);
    }

    public function terms(Request $request)
    {
        return view('other_page.terms');
    }

    public function return_policy(Request $request)
    {
        return view('other_page.privacy');
    }

    public function contact_us(Request $request)
    {
        return view('other_page.contact_us');
    }
}
