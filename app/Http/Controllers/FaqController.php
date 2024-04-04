<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Faqs'))
        // {
            $faqs = Faq::where('theme_id', APP_THEME())->where('store_id', getCurrentStore())->get();
            return view('faq.index',compact('faqs'));
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
        return view('faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if(auth()->user()->isAbleTo('Create Faqs'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'topic' => 'required',

                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $faq                 = new Faq();
            $faq->topic         = $request->topic;
            $faq->theme_id       = APP_THEME();
            $faq->store_id       = getCurrentStore();
            $faq->save();

            return redirect()->back()->with('success', __('FAQs successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return view('faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        // if(auth()->user()->isAbleTo('Edit Faqs'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'topic' => 'required',

                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $faq->topic               = $request->topic;
            $faq->description         = $request->kt_docs_repeater_basic;
            $faq->theme_id            = APP_THEME();
            $faq->save();

            return redirect()->back()->with('success', __('FAQs successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        
        // if(auth()->user()->isAbleTo('Delete Faqs'))
        // {
            $faq->delete();
            return redirect()->back()->with('success', __('FAQs delete successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
}
