<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Tag')) {
            $Tag = Tag::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();
            return view('tag.index' ,compact('Tag'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tag.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->isAbleTo('Create Tag')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $slug = Tag::slugs($request->name);
            $tag = new Tag();
            $tag->name = $request->name;
            $tag->slug = $slug;
            $tag->theme_id   = APP_THEME();
            $tag->store_id   = getCurrentStore();
            $tag->created_by = \Auth::user()->id;

            $tag->save();
            return redirect()->back()->with('success', __('Tag Created Successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
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
    public function edit(string $id)
    {
       $tag = Tag::find($id);
       return view('tag.edit' ,compact('tag'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {        
        if (auth()->user()->isAbleTo('Edit Tag')) {

            $tag = Tag::find($id);
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $tag->name  = $request->name;
            $tag->save();
            return redirect()->back()->with('success', __('Tag  Successfully Upadated'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        if (auth()->user()->isAbleTo('Delete Tag')) {
            $tag = Tag::find($id);
            $tag->delete();
            return redirect()->back()->with('success',__('Tag Deleted Successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
