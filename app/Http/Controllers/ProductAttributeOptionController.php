<?php

namespace App\Http\Controllers;

use App\Models\ProductAttributeOption;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Attributes Option')) {

        $ProductAttributes = ProductAttributeOption::where('theme_id',  APP_THEME())->where('store_id',getCurrentStore())->get();
        return view('attribute.index', compact('ProductAttributes'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        return view('attribute_option.create',compact('id'));


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request ,$id)
    {
        if (auth()->user()->isAbleTo('Create Attributes Option')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'terms' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $attribute_option                      = new ProductAttributeOption();
            $attribute_option->attribute_id        = $id;
            $attribute_option->terms                = $request->terms;
            $attribute_option->theme_id            = APP_THEME();
            $attribute_option->store_id            = getCurrentStore();

            $attribute_option->save();

            return redirect()->back()->with('success', __('Attribute successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductAttributeOption $productAttributeOption , $id)
    {
        $attribute = ProductAttribute::where('id', $id)->first();

        $attribute_option = ProductAttributeOption::where('attribute_id', $id)->orderBy('order','asc')->get();
        return view('attribute_option.index', compact('productAttributeOption','attribute_option','attribute'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $productAttributeOption = ProductAttributeOption::findOrFail($id);

        return view('attribute_option.edit', compact('productAttributeOption'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductAttributeOption $productAttributeoption,$id)
    {
        
        if (auth()->user()->isAbleTo('Edit Attributes Option')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'terms' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $productAttributeoption           = ProductAttributeOption::find($id);
            $productAttributeoption->terms       = $request->terms;
            $productAttributeoption->save();
            return redirect()->back()->with('success', __('Attribute Option successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAttributeOption $productAttributeOption ,$id)
    {
        
        if (auth()->user()->isAbleTo('Delete Attributes Option')) {

            $productAttributeOption = ProductAttributeOption::findOrFail($id);
            $productAttributeOption->delete();

            return redirect()->back()->with('success', __('Attribute option delete successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function option(Request $request)
    {
        $post = $request->all();
        foreach($post['terms'] as $key => $item)
        {
            $status        = ProductAttributeOption::where('id', '=', $item)->first();
            $status->order = $key;
            $status->save();
        }
    }
}
