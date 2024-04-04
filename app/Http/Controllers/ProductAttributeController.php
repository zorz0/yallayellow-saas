<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use App\Models\User;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Attributes')) {

                $ProductAttributes = ProductAttribute::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();

                return view('attributes.index',compact('ProductAttributes'));
         } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->isAbleTo('Create Attributes')) {
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
            $slug = ProductAttribute::slugs($request->name);
            $attribute                      = new ProductAttribute();
            $attribute->name                = $request->name;
            $attribute->slug                = $slug;
            $attribute->theme_id            = APP_THEME();
            $attribute->store_id            = getCurrentStore();
            $attribute->save();
            return redirect()->back()->with('success', __('Attribute successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductAttribute $productAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductAttribute $productAttribute)
    {
        return view('attributes.edit', compact('productAttribute'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductAttribute $productAttribute)
    {
        
        if (auth()->user()->isAbleTo('Edit Attributes')) {

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
            $productAttribute->name       = $request->name;
            $productAttribute->save();

            return redirect()->back()->with('success', __('Attribute successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAttribute $productAttribute)
    {
        
        if (auth()->user()->isAbleTo('Delete Attributes')) {

            $productAttribute->delete();
            return redirect()->back()->with('success', __('Attribute delete successfully.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
