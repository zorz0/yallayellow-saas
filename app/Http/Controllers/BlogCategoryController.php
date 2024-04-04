<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAbleTo('Manage Blog Category'))
        {
            $blogCategory = BlogCategory::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();
            return view('blog-category.index', compact('blogCategory'));
        }else{
            return redirect()->back()->with('error',__('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if(auth()->user()->isAbleTo('Create Blog Category'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required'
                                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            $blogCategory = new BlogCategory();
            $blogCategory->name         = $request->name;
            $blogCategory->status       = $request->status;
            $blogCategory->theme_id     = APP_THEME();
            $blogCategory->store_id     = getCurrentStore();

            $blogCategory->save();

            return redirect()->back()->with('success', __('Category successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $blogCategory = BlogCategory::find($id);
        return view('blog-category.edit', compact('blogCategory'));
    }

    public function update(Request $request, $id)
    {
        
        // if(auth()->user()->isAbleTo('Edit Blog Category'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $blogCategory = BlogCategory::find($id);

            $blogCategory->name = $request->name;


            $blogCategory->status       = $request->status;
            $blogCategory->save();

            return redirect()->back()->with('success', __('Category successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function destroy($id)
    {
        
        // if(auth()->user()->isAbleTo('Delete Blog Category'))
        // {
            $blogCategory = BlogCategory::find($id);
            $blogCategory->delete();
            return redirect()->back()->with('success', __('Category delete successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
}
