<?php

namespace App\Http\Controllers;

use App\Models\Webhook;
use Illuminate\Http\Request;
use App\Models\Store;

class WebhookController extends Controller
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
        return view('webhook.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         
        $store = Store::find(getCurrentStore());

        $validator = \Validator::make(
            $request->all(),
            [
                'module' => 'required|unique:webhooks,module,NULL,id,store_id,' . $store->id . ',theme_id,' . $store->theme_id,
                'method' => 'required',
                'webbbook_url' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $webhook            = new webhook();
        $webhook->module    = $request->module;
        $webhook->url       = $request->webbbook_url;
        $webhook->method    = $request->method;
        $webhook->store_id  = $store->id;
        $webhook->theme_id  = $store->theme_id;
        $webhook->save();

        return redirect()->back()->with('success', __('Webhook setting created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Webhook $webhook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $store = \Auth::user()->current_store;
        $webhook = webhook::where('id', $id)->where('store_id', $store)->get();
        return view('webhook.edit', compact('webhook'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         
        $store = Store::find(getCurrentStore());

        $validator = \Validator::make(
            $request->all(),
            [
                'module' => 'required|unique:webhooks,module,' . $id . ',id,store_id,' . $store->id . ',theme_id,' . $store->theme_id,
                'method' => 'required',
                'webbbook_url' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $webhook['module']      =   $request->module;
        $webhook['method']      =   $request->method;
        $webhook['url']         =   $request->webbbook_url;
        $webhook['store_id']    =   $store->id;
        webhook::where('id', $id)->update($webhook);

        return redirect()->back()->with('success', __('Webhook Setting Succssfully Updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        if (\Auth::user()->type == 'admin') {
            $webhook = webhook::find($id);
            if ($webhook) {
                $webhook->delete();
                return redirect()->back()->with('success', __('Webhook Setting successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
