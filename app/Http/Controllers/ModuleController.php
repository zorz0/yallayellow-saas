<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Artisan;
use App\Models\AddOnManager;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use ZipArchive;
use App\Models\Addon;
use App\Models\Utility;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(Auth::user()->isAbleTo('Module manage'))
        // {
            $modules = Module::all();
            $module_path = Module::getPath();
            $addon_themes = Addon::get();
            $theme = Utility::BuyMoreTheme();
            // $category_wise_add_ons = json_decode(file_get_contents("https://demo.workdo.io/cronjob/dash-addon.json"),true);

            return view('module.index',compact('modules','module_path', 'theme', 'addon_themes'));
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function enable(Request $request)
    {
        $module = Module::find($request->name);
        if(!empty($module))
        {
            // Sidebar Performance Changes
            sideMenuCacheForget('all');

            \App::setLocale('en');
            if($module->isEnabled())
            {
                $check_child_module = $this->Check_Child_Module($module);

                if($check_child_module == true)
                {
                    $module->disable();
                    return redirect()->back()->with('success', __('Module Disable Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __($check_child_module['msg']));
                }

            }
            else
            {
                $check_parent_module = $this->Check_Parent_Module($module);
               
                if($check_parent_module['status'] == true)
                {
                    Artisan::call('module:migrate '. $request->name);
                    Artisan::call('module:seed '. $request->name);
                    // $this->installDependencies($request->name);
                    // Artisan::call('module:update '. $request->name);

                    $addon = AddOnManager::where('module',$request->name)->first();

                    if(empty($addon))
                    {
                        $addon = new AddOnManager;
                        $addon->module = $request->name;
                        $addon->name = Module_Alias_Name($request->name);
                        $addon->monthly_price = 0;
                        $addon->yearly_price = 0;

                        $addon->save();
                    }
                    $module->enable();
                    // Artisan::call('module:migrate-rollback '.$module);
                    return redirect()->back()->with('success', __('Module Enable Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __($check_parent_module['msg']));
                }

            }
        }else{
            return redirect()->back()->with('error', __('oops something wren wrong!'));
        }
    }

    public function remove($module)
    {
        // if(Auth::user()->isAbleTo('module remove'))
        // {
            $module = Module::find($module);
            if($module)
            {
                $module->disable();

               $module->delete();
                Permission::where('module',$module)->delete();
                Artisan::call('module:migrate-refresh '.$module);
                AddOnManager::where('module',$module)->delete();

                // Sidebar Performance Changes
                sideMenuCacheForget('all');
                return redirect()->back()->with('success', __('Module delete successfully!'));
            }
            else
            {
                return redirect()->back()->with('error', __('oops something wren wrong!'));
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function Check_Child_Module($module)
    {
        $path =$module->getPath().'/module.json';
        $json = json_decode(file_get_contents($path), true);
        $status = true;
        if(isset($json['child_module']) && !empty($json['child_module']))
        {
            foreach ($json['child_module'] as $key => $value)
            {
                $child_module = module_is_active($value);
                if($child_module == true)
                {
                    $module = Module::find($value);
                    $module->disable();
                    if($module)
                    {
                        $this->Check_Child_Module($module);
                    }
                }
            }
            return true;
        }
        else
        {
            return true;
        }
    }

    public function Check_Parent_Module($module)
    {
        $path =$module->getPath().'/module.json';
        $json = json_decode(file_get_contents($path), true);
        $data['status'] = true;
        $data['msg'] = '';

        if(isset($json['parent_module']) && !empty($json['parent_module']))
        {
            foreach ($json['parent_module'] as $key => $value) {
                $modules = implode(',',$json['parent_module']);
                $parent_module = module_is_active($value);
                if($parent_module == true)
                {
                    $module = Module::find($value);
                    if($module)
                    {
                         $this->Check_Parent_Module($module);
                    }
                }
                else
                {
                    $data['status'] = false;
                    $data['msg'] = 'please activate this module '.$modules;
                    return $data;
                }
            }
            return $data;
        }
        else
        {
            return $data;
        }
    }

    public function install(Request $request){
        $zip = new ZipArchive;
        try {
                $res = $zip->open($request->file);
          } catch (\Exception $e) {
                return error_res($e->getMessage());
          }
        if ($res === TRUE)
        {
            $zip->extractTo('Modules/');
            $zip->close();
            $return['status'] = 'success';
            $return['message'] = __('Install successfully.');
            return response()->json($return);
        } else {
            $return['status'] = 'error';
            $return['message'] = __('oops something went wrong!!');
            return response()->json($return);
        }
        $return['status'] = 'error';
        $return['message'] = __('oops something went wrong!!');
        return response()->json($return);
    }

    public function add(){
        // if(auth()->user()->isAbleTo('Module add'))
        // {
            return view('module.add');
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

}
