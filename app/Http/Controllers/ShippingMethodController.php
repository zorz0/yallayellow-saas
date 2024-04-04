<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use App\Models\ShippingZone;
use App\Models\Shipping;

class ShippingMethodController extends Controller
{
    public function index()
    {
        //
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
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingMethod $shippingMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shippingMethod = ShippingMethod::find($id);
        $shippings = Shipping::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get()->pluck('name', 'id');
        $shippings_count = Shipping::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get()->pluck('name', 'id')->count();
        // dd();
        return view('shipping_method.edit',compact('shippingMethod','shippings','shippings_count'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $shipping                   = ShippingMethod::find($id);
        $shipping->cost             = $request->cost;
        $shipping->calculation_type = $request->calculation_type;
        $shipping->theme_id         = APP_THEME();
        $shipping->store_id         = getCurrentStore();

        $data = [];
        $data['product_cost'] = $request->product_cost;
        $data['product_no_cost'] = !empty($request->product_no_cost) ? $request->product_no_cost : 0;
        $data = json_encode($data);
        $shipping->product_cost     = $data;

        $shipping->save();

        return redirect()->back()->with('success', __('Shipping successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShippingMethod  $shippingMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        //
    }

    public function freeShippingEdit($id)
    {
        $shippingMethod = ShippingMethod::find($id);
        return view('shipping_method.free_shipping',compact('shippingMethod'));
    }

    public function freeShippingUpdate(Request $request, $id)
    {
        
        $shipping                    = ShippingMethod::find($id);
        $shipping->shipping_requires = $request->shipping_requires;
        $shipping->theme_id          = APP_THEME();
        $shipping->store_id          = getCurrentStore();
        if($shipping->shipping_requires == '1' || $shipping->shipping_requires == '2')
        {
            $shipping->cost     = '0';
        }
        else
        {
            $shipping->cost     = $request->cost;
        }
        $shipping->save();
        return redirect()->back()->with('success', __('Shipping successfully updated.'));

    }


    public function localShippingEdit($id)
    {
        $shippingMethod = ShippingMethod::find($id);

        return view('shipping_method.local_shipping',compact('shippingMethod'));
    }

    public function localShippingUpdate(Request $request, $id)
    {
        
        $validator = \Validator::make(
            $request->all(),
            [
                'cost' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $shipping                   = ShippingMethod::find($id);
        $shipping->cost             = $request->cost;
        $shipping->theme_id         = APP_THEME();
        $shipping->store_id         = getCurrentStore();
        $shipping->save();

        return redirect()->back()->with('success', __('Shipping successfully updated.'));
    }
}
