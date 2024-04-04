<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $country,$state;
    public function __construct(Country $country, State $state)
    {
        $this->country = $country;
        $this->state = $state;
    }
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
        if (Auth::user()->type == 'super admin') {
            $countries = Country::pluck('name','id');
            return view('State.create',compact('countries'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $state = new State();
        $state->name = $request->name;
        $state->country_id = $request->country_id;
        $state->save();

        return redirect()->back()->with('success', __('state successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(State $state)
    {
        //
        if (Auth::user()->type == 'super admin') {

            $country = Country::find($state->country_id);
            $countries = Country::all()->pluck('name','id');


            return view('State.edit',compact('state','country','countries'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,State $state)
    {
        
        $state->name = $request->state;
        $state->country_id= $request->country;
        $state->save();
        return redirect()->back()->with('success', __('state successfully created.'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        
        if (Auth::user()->type == 'super admin') {
            $state->delete();
            return redirect()->back()->with('success', __('Country delete successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getState(Request $request)
    {

        $query = City::where('state_id' , '=' ,$request->state);
        if (!empty($request->state)) {
            $query->where('state_id', '=', $request->state);
        }
        $filter_state = $query->get();

        $filter = view('State.filter', compact('filter_state'))->render();

        return response()->json($filter);
    }

    public function getAllState()
    {
        $state = State::all()->toArray();
        return $state ;
    }


 public function getCityState(Request $request)
 {
     if ($request->country_id == 0) {
         $departments = State::get()->pluck('name', 'id')->toArray();
     } else {
         $departments = State::where('country_id', $request->country_id)->get()->pluck('name', 'id')->toArray();
     }
     return response()->json($departments);
 }
}
