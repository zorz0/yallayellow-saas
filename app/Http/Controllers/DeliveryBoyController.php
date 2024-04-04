<?php

namespace App\Http\Controllers;

use App\Models\DeliveryBoy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DeliveryBoyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (\Auth::user()->isAbleTo('Manage Deliveryboy'))
        // {
            $deliveryboys = DeliveryBoy::where('store_id',getCurrentStore())->where('theme_id',APP_THEME())->get();
            return view('deliveryboy.index',compact('deliveryboys'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // if (\Auth::user()->isAbleTo('Create DeliveryBoy'))
        // {
            return view('deliveryboy.create');
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (\Auth::user()->isAbleTo('Create DeliveryBoy'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'contact' => 'required|regex:/^\d{10}$/',
                ]
            );
            
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
    
                return redirect()->back()->with('error', $messages->first());
            }

        $user = \Auth::user();

        $deliveryboy = new DeliveryBoy();
        $deliveryboy->name = $request->name;
        $deliveryboy->email = $request->email;
        $deliveryboy->profile_image = 'uploads/profile/avatar.png';
        $deliveryboy->type = $request->type;
        $deliveryboy->password = Hash::make($request->password);
        $deliveryboy->contact = $request->contact;
        $deliveryboy->created_by = $user->id;
        $deliveryboy->theme_id     = APP_THEME();
        $deliveryboy->store_id     = getCurrentStore();

        $deliveryboy->save();

        return redirect()->back()->with('success', __('DeliveryBoy successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryBoy $deliveryBoy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        // if (\Auth::user()->isAbleTo('Edit Deliveryboy'))
        // {
            $deliveryBoy = DeliveryBoy::find($id);
            return view('deliveryboy.edit',compact('deliveryBoy'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        
        // if (\Auth::user()->isAbleTo('Edit Deliveryboy'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'email' => [
                        'required|email',
                    ],
                    'contact' => [
                        'required','regex:/^\d{10}$/'
                    ],
                ]
            );
            
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $deliveryBoy = DeliveryBoy::find($id);
    
            $deliveryBoy->name     = $request->name;
            $deliveryBoy->email    = $request->email;
            $deliveryBoy->contact  = $request->contact;
            $deliveryBoy->save();
    
            return redirect()->back()->with('success', __('DeliveryBoy successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        // if (\Auth::user()->isAbleTo('Delete Deliveryboy'))
        // {
            $deliveryBoy = DeliveryBoy::find($id);
            $deliveryBoy->delete();
            return redirect()->back()->with('success', __('DeliveryBoy successfully Deleted!'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function resetPassword($id)
    {
        // if (\Auth::user()->isAbleTo('Reset password Deliveryboy'))
        // {
            $Id        = \Crypt::decrypt($id);
            $deliveryBoy = DeliveryBoy::find($Id);

            return view('deliveryboy.reset', compact('deliveryBoy'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function updatePassword(Request $request, $id)
    {
        
        // if (\Auth::user()->isAbleTo('Reset password Deliveryboy'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'password' => 'required|confirmed|same:password_confirmation',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $deliveryBoy = DeliveryBoy::find($id);
            $deliveryBoy->forceFill([
                'password' => Hash::make($request->password),
            ])->save();

            return redirect()->back()->with( 'success', __('DeliveryBoy successfully updated!'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    
}
