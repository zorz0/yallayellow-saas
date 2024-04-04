<?php

namespace App\Http\Controllers;

use App\Models\OrderNote;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderNoteController extends Controller
{
    //


    public function create()
    {
        //
        // return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->isAbleTo('Create Order Note')) {
            $store_id = Store::where('id', getCurrentStore())->first();
            $note                 = new OrderNote;
            $note->order_id       = $request->order_id;
            $note->notes          = $request->note;
            $note->note_type      = $request->note_type;
            $note->theme_id       = $store_id->theme_id;
            $note->store_id       = getCurrentStore();
            $note->save();

            return redirect()->back()->with('success', __('Order Note successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    public function destroy(OrderNote $OrderNote)
    {
        
        if (auth()->user()->isAbleTo('Delete Order Note')) {
            $OrderNote->delete();
            return redirect()->back()->with('success', __('Note delete successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
