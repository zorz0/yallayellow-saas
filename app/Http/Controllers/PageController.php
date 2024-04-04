<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Utility;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Page'))
        // {
            $pages = Page::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();

            return view('page.index',compact('pages'));

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
        return view('page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if(auth()->user()->isAbleTo('Create Page'))
        // {
            $theme_id = APP_THEME();
            $validator = \Validator::make(
                $request->all(),
                [
                    'page_name' => 'required',
                    'page_slug' => [
                        'required',
                        Rule::unique('pages')->where(function ($query)  use ($theme_id) {
                            return $query->where('theme_id',APP_THEME());
                        })
                    ],
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $page = new Page();
            $page->page_name = $request->page_name;
            $page->page_slug = $request->page_slug;
            $page->page_content = $request->page_content;
            $page->page_meta_title = $request->page_meta_title;
            $page->page_meta_description = $request->page_meta_description;
            $page->page_meta_keywords = implode(',', $request->page_meta_keywords);
            $page->theme_id = APP_THEME();
            $page->store_id = getCurrentStore();
            $page->save();

            return redirect()->back()->with('success', __('Page successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $page = Page::find($page->id);
        $page_meta_keywords = explode(',', $page->page_meta_keywords);
        return view('page.edit', compact('page', 'page_meta_keywords'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {

        // if(auth()->user()->isAbleTo('Edit Menu'))
        // {
            $theme_id = APP_THEME();
            $validator = \Validator::make(
                $request->all(),
                [
                    'page_name' => 'required',
                    'page_slug' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $page = Page::find($page->id);
            $page->page_name = $request->page_name;
            $page->page_slug = $request->page_slug;
            $page->page_content = $request->page_content;
            $page->page_meta_title = $request->page_meta_title;
            $page->page_meta_description = $request->page_meta_description;
            $page->page_meta_keywords = $request->page_meta_keywords;
            $page->theme_id = APP_THEME();
            $page->store_id = getCurrentStore();
            $page->save();
            if ($page) {
                return redirect()->back()->with(['success' => __('Page updated successfully.')]);
            } else {
                return redirect()->back()->with(['error' => __('Someting went wrong.')]);
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {

        // if(auth()->user()->isAbleTo('Delete Page'))
        // {
            Page::findOrFail($page->id)->delete();
            return redirect()->route('pages.index')->with('success', __('Page deleted successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function updateStatus(Request $request)
    {
        $pageId = $request->input('pageId');
        $isActivated = $request->input('isActivated');
        $page = Page::findOrFail($pageId);
        if ($isActivated == 'true') {
            $page->page_status = 1;
        } else {
            $page->page_status = 0;
        }
        $page->save();
    }
}
