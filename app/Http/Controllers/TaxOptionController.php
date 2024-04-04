<?php

namespace App\Http\Controllers;

use App\Models\TaxOption;
use App\Models\Store;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxOptionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxOption $taxOption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxOption $taxOption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxOption $taxOption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxOption $taxOption)
    {
        //
    }

    public function taxSettings(Request $request)
    {
         
        // if(auth()->user()->isAbleTo('Manage Tax-option'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'price_type' => 'required',
                    'shop_price' => 'required',
                    'checkout_price' => 'required',

                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            // Prepare data for database insertion/update
            $post['price_type']          = $request->price_type;
            $post['round_tax']           = $request->round_tax;
            if(!empty($request->tax_id)){
                $post['tax_id'] = $request->tax_id;
            }else{
                $tax = Tax::where('store_id' ,getCurrentStore())->where('theme_id',APP_THEME())->first();
                $post['tax_id'] = $tax->id ?? null;
            }
            $post['display_tax_option']  = $request->display_tax_option;
            $post['price_suffix']        = $request->price_suffix;

            if ($request->price_type == 'inclusive' && ($request->shop_price != 'including' || $request->checkout_price != 'including')) {
                $post['shop_price'] = 'including';
                $post['checkout_price'] = 'including';
                $messages = 'Consistent tax settings: Prices should be uniform with or without taxes to prevent rounding errors.';
            } elseif ($request->price_type == 'exclusive' && ($request->shop_price != 'exclusive' || $request->checkout_price != 'exclusive')) {
                $post['shop_price'] = 'exclusive';
                $post['checkout_price'] = 'exclusive';
                $messages = 'Consistent tax settings: Prices should be uniform with or without taxes to prevent rounding errors.';
            } else {
                $post['shop_price'] = $request->shop_price;
                $post['checkout_price'] = $request->checkout_price;
                $messages = 'Tax-option setting successfully updated.';
            }
            
            // Iterate over the data and insert/update in the 'tax_options' table
            foreach ($post as $key => $data) {
                TaxOption::updateOrCreate(
                    [
                        'name' => $key,
                        'theme_id' => APP_THEME(),
                        'store_id' => getCurrentStore()
                    ],
                    [
                        'value'         => $data,
                        'name'          => $key,
                        'theme_id'      => APP_THEME(),
                        'store_id'      => getCurrentStore(auth()->user()->id, null),
                        'created_by'    => auth()->user()->id,
                    ]
                );
            }

        // Redirect back with success message
        return redirect()->back()->with('success', $messages);
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
}
