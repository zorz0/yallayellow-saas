<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\ShippingZone;
use App\Models\ShippingMethod;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Shipping Class'))
        {
            $shippings = Shipping::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();
            return view('shipping.index', compact('shippings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shipping.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->isAbleTo('Create Shipping Class'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $slug = str_replace(' ', '-', strtolower($request->name));
            $shipping                   = new Shipping();
            $shipping->name             = $request->name;
            $shipping->slug             = $slug;
            $shipping->description      = $request->description;
            $shipping->theme_id         = APP_THEME();
            $shipping->store_id         = getCurrentStore();
            $shipping->save();
            return redirect()->back()->with('success', __('Shipping successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $shipping = Shipping::find($id);
        return view('shipping.edit', compact('shipping'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        if (auth()->user()->isAbleTo('Edit Shipping Class'))
        {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $shipping = Shipping::find($id);
            $slug = str_replace(' ', '-', strtolower($request->name));
            $shipping->name         = $request->name;
            $shipping->slug         = $slug;
            $shipping->description  = $request->description;
            $shipping->save();
            return redirect()->back()->with('success', __('Shipping successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        if (auth()->user()->isAbleTo('Delete Shipping Class'))
        {
            $shipping = Shipping::find($id);
            $shipping->delete();
            return redirect()->back()->with('success', __('Shipping delete successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
