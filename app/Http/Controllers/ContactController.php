<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Contact Us'))
        // {
            // $ThemeSubcategory = Utility::addThemeSubcategory();
            $contacts = Contact::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();
            return view('contact.index',compact('contacts'));

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'contact' => 'required',
                'subject' => 'required',
                'description' => 'required',
            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $contact                    = new Contact();
        $contact->first_name        = $request->first_name;
        $contact->last_name         = $request->last_name;
        $contact->email             = $request->email;
        $contact->contact           = $request->contact;
        $contact->subject           = $request->subject;
        $contact->description       = $request->description;
        $contact->theme_id          = APP_THEME();
        $contact->store_id          = getCurrentStore();
        $contact->save();

        return redirect()->back()->with('success', __('Contact successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        return view('contact.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        
        // if(auth()->user()->isAbleTo('Edit Contact Us'))
        // {
            $validator = \Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required',
                    'contact' => 'required',
                    'subject' => 'required',
                    'description' => 'required',

                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $contact->first_name     = $request->first_name;
            $contact->last_name      = $request->last_name;
            $contact->email          = $request->email;
            $contact->contact        = $request->contact;
            $contact->subject        = $request->subject;
            $contact->description    = $request->description;
            $contact->theme_id       = APP_THEME();
            $contact->save();

            return redirect()->back()->with('success', __('Contact successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        
        // if(auth()->user()->isAbleTo('Delete Contact Us'))
        // {
            $contact->delete();
            return redirect()->back()->with('success', __('Contact delete successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

}
