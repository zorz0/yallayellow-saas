<?php

namespace App\Http\Controllers;

use App\Models\TaxMethod;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class TaxMethodController extends Controller
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
    public function create($tax_option_id)
    {
        //
        $country_option = Country::pluck('name', 'id')->prepend('Select country', ' ');
        return view('taxes-method.create',compact('country_option','tax_option_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // if(auth()->user()->isAbleTo('Create Tax Method'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'tax_rate' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $taxmethod                        = new TaxMethod();
            $taxmethod->name                  = $request->name;
            $taxmethod->tax_rate              = $request->tax_rate;
            $taxmethod->tax_id                = $request->tax_id;
            $taxmethod->country_id            = $request->country_id;
            $taxmethod->state_id              = $request->state_id;
            $taxmethod->city_id               = $request->city_id;
            $taxmethod->priority              = $request->priority;
            $taxmethod->enable_shipping       = $request->enable_shipping;
            $taxmethod->theme_id              = APP_THEME();
            $taxmethod->store_id              = getCurrentStore();
            $taxmethod->save();

        // Redirect back with success message
        return redirect()->back()->with('success', __('Tax-method successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxMethod $taxMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaxMethod $taxMethod)
    {
        //
        $tax_method = TaxMethod::find($taxMethod->id);
        $country_option = Country::pluck('name', 'id')->prepend('Select country', ' ');
        $state_option = State::pluck('name', 'id')->prepend('Select state', ' ');
        $city_option = City::pluck('name', 'id')->prepend('Select state', ' ');
        return view('taxes-method.edit', compact('tax_method','country_option','state_option','city_option'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxMethod $taxMethod)
    {
        
        // if(auth()->user()->isAbleTo('Edit Tax Method'))
        // {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'tax_rate' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $taxmethod = TaxMethod::find($taxMethod->id);
            $taxmethod->name                 = $request->name;
            $taxmethod->tax_rate              = $request->tax_rate;
            $taxmethod->country_id            = $request->country_id;
            $taxmethod->state_id              = $request->state_id;
            $taxmethod->city_id               = $request->city_id;
            $taxmethod->priority              = $request->priority;
            $taxmethod->enable_shipping       = $request->enable_shipping;
            $taxmethod->theme_id              = APP_THEME();
            $taxmethod->store_id              = getCurrentStore();
            $taxmethod->save();

            return redirect()->back()->with('success', __('Tax-method successfully updated.'));

        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxMethod $taxMethod)
    {
        
        // if(auth()->user()->isAbleTo('Delete Tax Method'))
        // {
            $tax = TaxMethod::find($taxMethod->id);
            $tax->delete();
            return redirect()->back()->with('success', __('Tax-method delete successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }

    }

    public function cities_list(Request $request)
    {
        $state_id = $request->state_id;
        $cities_list = City::where('state_id',$state_id)->pluck('name', 'id')->prepend('Select option',0)->toArray();
        return response()->json($cities_list);

    }
}
