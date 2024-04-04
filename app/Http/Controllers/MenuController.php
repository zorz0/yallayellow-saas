<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MainCategory;
use App\Models\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(auth()->user()->isAbleTo('Manage Menu'))
        // {
            $menus = Menu::where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->get();

            return view('menu.index',compact('menus'));

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
        return view('menu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if(auth()->user()->isAbleTo('Create Menu'))
        // {
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

            $menus                        = new Menu();
            $menus->name                  = $request->name;
            $menus->theme_id              = APP_THEME();
            $menus->store_id              = getCurrentStore();
            $menus->save();
            return redirect()->back()->with('success', __('Menus successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        // if(auth()->user()->isAbleTo('Edit Menu'))
        // {
            $menuitems = '';
            $desiredMenu = '';
            if (isset($menu->id)) {
                $desiredMenu = Menu::where('id', $menu->id)->first();
                if ($desiredMenu->content != '') {
                    $menuitems = json_decode($desiredMenu->content);
                    $menuitems = $menuitems[0] ?? [];
                    foreach ($menuitems as $menu) {
                        $menu->title    = MenuItem::where('id', $menu->id)->value('title');
                        $menu->slug     = MenuItem::where('id', $menu->id)->value('slug');
                        $menu->target   = MenuItem::where('id', $menu->id)->value('target');
                        $menu->type     = MenuItem::where('id', $menu->id)->value('type');
                        if (!empty($menu->children[0])) {
                            foreach ($menu->children[0] as $child) {
                                $child->title   = MenuItem::where('id', $child->id)->value('title');
                                $child->slug    = MenuItem::where('id', $child->id)->value('slug');
                                $child->target  = MenuItem::where('id', $child->id)->value('target');
                                $child->type    = MenuItem::where('id', $child->id)->value('type');
                            }
                        }
                    }
                } else {
                    $menuitems = MenuItem::where('menu_id', $desiredMenu->id)->get();
                }
                $pages      = Page::where('page_status', 1)->where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->pluck('page_name', 'id');
                $categories = MainCategory::where('status', 1)->where('theme_id', APP_THEME())->where('store_id',getCurrentStore())->pluck('name', 'id');
                return view('menu.edit', compact('categories', 'desiredMenu', 'menuitems','pages'));
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        // if(auth()->user()->isAbleTo('Delete Menu'))
        // {
            MenuItem::where('menu_id', $id)->delete();
            Menu::findOrFail($id)->delete();
            return redirect()->route('menus.index')->with('success', __('Menu deleted successfully.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function addCatToMenu(Request $request)
    {
        $data       = $request->all();
        $menuid     = $request->menuid;
        $ids        = $request->ids;
        $menu       = Menu::findOrFail($menuid);

        if ($menu->content == '') {
            foreach ($ids as $id) {
                $title      = MainCategory::where('id', $id)->value('name');
                $slug       = MainCategory::where('id', $id)->value('slug');
                $menuData[] = [
                    'title'         => $title,
                    'slug'          => $slug,
                    'type'          => 'category',
                    'menu_id'       => $menuid,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ];
            }
            MenuItem::insert($menuData);
        } else {
            $olddata = json_decode($menu->content, true);
            foreach ($ids as $id) {
                $title          = MainCategory::where('id', $id)->value('name');
                $slug           = MainCategory::where('id', $id)->value('slug');
                $menuData[] = [
                    'title'         => $title,
                    'slug'          => $slug,
                    'type'          => 'category',
                    'menu_id'       => $menuid,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ];
            }
            MenuItem::insert($menuData);
            foreach ($ids as $id) {
                $title              = MainCategory::where('id', $id)->value('name');
                $slug               = MainCategory::where('id', $id)->value('slug');
                $array['title']     = $title;
                $array['slug']      = $slug;
                $array['name']      = NULL;
                $array['type']      = 'category';
                $array['target']    = NULL;
                $array['id']        = MenuItem::where('slug', $array['slug'])->where('title', $array['title'])->where('type', $array['type'])->value('id');
                $array['children']  = [[]];
                array_push($olddata[0], $array);
                $oldata = json_encode($olddata);
                $menu->update(['content' => $oldata]);
            }
        }
    }

    public function updateMenuItem(Request $request)
    {
        
        // if(auth()->user()->isAbleTo('Edit Menu'))
        // {
            $data           = $request->all();
            $item           = MenuItem::findOrFail($request->id);
            $data['target'] = (isset($request->target)) ? '_blank' : '';
            $item->update($data);
            return redirect()->back();
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function deleteMenuItem($id, $key, $in = '')
    {
        
        // if(auth()->user()->isAbleTo('Delete Menu'))
        // {
            $menuitem   = MenuItem::findOrFail($id);
            $menu       = Menu::where('id', $menuitem->menu_id)->first();
            if ($menu->content != '') {
                $data       = json_decode($menu->content, true);
                $maindata   = $data[0];
                if ($in == '') {
                    unset($data[0][$key]);
                    $newdata = json_encode($data);
                    $menu->update(['content' => $newdata]);
                } else {
                    unset($data[0][$key]['children'][0][$in]);
                    $newdata = json_encode($data);
                    $menu->update(['content' => $newdata]);
                }
            }
            $menuitem->delete();
            return redirect()->back();
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }
    public function addPageToMenu(Request $request)
    {
        $menuid     = $request->menuid;
        $ids        = $request->ids;
        $menu       = Menu::findOrFail($menuid);
        if ($menu->content == '') {
            foreach ($ids as $id) {
                $title      = Page::where('id', $id)->value('page_name');
                $slug       = Page::where('id', $id)->value('page_slug');
                $pageData[] = [
                    'title'         => $title,
                    'slug'          => $slug,
                    'type'          => 'page',
                    'menu_id'       => $menuid,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ];
            }
            MenuItem::insert($pageData);
        } else {
            $olddata = json_decode($menu->content, true);
            foreach ($ids as $id) {
                $title  = Page::where('id', $id)->value('page_name');
                $slug   = Page::where('id', $id)->value('page_slug');
                $pageData[] = [
                    'title'         => $title,
                    'slug'          => $slug,
                    'type'          => 'page',
                    'menu_id'       => $menuid,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ];
            }
            MenuItem::insert($pageData);
            foreach ($ids as $id) {
                $array['title']     = Page::where('id', $id)->value('page_name');
                $array['slug']      = Page::where('id', $id)->value('page_slug');
                $array['type']      = 'page';
                $array['target']    = NULL;
                $array['id']        = MenuItem::where('slug', $array['slug'])->where('title', $array['title'])->where('type', $array['type'])->orderby('id', 'DESC')->value('id');
                $array['children']  = [[]];
                array_push($olddata[0], $array);
                $oldata = json_encode($olddata);
                $menu->update(['content' => $oldata]);
            }
        }
    }

    public function addLinkToMenu(Request $request)
    {
        $data       = $request->all();
        $menuid     = $request->menuid;
        $menu       = Menu::findOrFail($menuid);
        if ($menu->content == '') {
            $data['title']      = $request->link;
            $data['slug']       = $request->url;
            $data['type']       = 'custom';
            $data['menu_id']    = $menuid;
            MenuItem::create($data);
        } else {
            $olddata            = json_decode($menu->content, true);
            $data['title']      = $request->link;
            $data['slug']       = $request->url;
            $data['type']       = 'custom';
            $data['menu_id']    = $menuid;
            menuitem::create($data);
            $array              = [];
            $array['title']     = $request->link;
            $array['slug']      = $request->url;
            $array['type']      = 'custom';
            $array['target']    = NULL;
            $array['id']        = menuitem::where('slug', $array['slug'])->where('title', $array['title'])->where('type', $array['type'])->orderby('id', 'DESC')->value('id');
            $array['children']  = [[]];
            array_push($olddata[0], $array);
            $oldata = json_encode($olddata);
            $menu->update(['content' => $oldata]);
        }
    }

    public function updateMenu(Request $request)
    {
        
        // if(auth()->user()->isAbleTo('Edit Menu'))
        // {
            $newdata                = $request->all();
            $menu                   = Menu::findOrFail($request->menuid);
            $content                = $request->data;
            $newdata['location']    = $request->location;
            $newdata['content']     = json_encode($content);
            $menu->update($newdata);
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

}
