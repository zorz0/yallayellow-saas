<?php

namespace App\Providers;

use App\Models\Store;
use Illuminate\View\FileViewFinder;
use Illuminate\View\ViewServiceProvider as ConcreteViewServiceProvider;
use DB;

class ViewServiceProvider extends ConcreteViewServiceProvider
{
    /**
     * Register the view finder implementation.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        if(file_exists(storage_path() . "/installed"))
        {
            $local = parse_url(config('app.url'))['host'];
            // Get the request host
            $remote = request()->getHost();
            // Get the remote domain
            // remove WWW
            $remote = str_replace('www.', '', $remote);
         
            $subdomain = DB::table('settings')->where('name','subdomain')->where('value',$remote)->first();
            $domain = DB::table('settings')->where('name','domains')->where('value',$remote)->first();
    
            if($subdomain || $domain ){
                if($subdomain){
                    $enable_subdomain = DB::table('settings')->where('name','enable_subdomain')->where('value','on')->where('store_id',$subdomain->store_id)->first();
                    if($enable_subdomain){
                    $admin = DB::table('admins')->find($enable_subdomain->created_by);
                        if($enable_subdomain->value == 'on' &&  $enable_subdomain->store_id == $admin->current_store){
                            $store = DB::table('stores')->find($admin->current_store);
                            if($store){
                              
                                \Config::set('view.paths',[resource_path('views'), base_path('themes/'.$store->theme_id.'/views')]);
                            }
                        }
                        else{
                            if(request()->segments())
                            {
                                $slug =request()->segments()[0];
                    
                                if($slug != 'admin' && $slug != 'logout' && $slug != 'install' && $slug != 'api')
                                {
                                    $store = DB::table('stores')->where('slug',$slug)->first();
                                    
                                    if($store)
                                    {
                                        \Config::set('view.paths',[
                                            resource_path('views'),
                                            base_path('themes/'.$store->theme_id.'/views')
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
    
                if($domain){
                    $enable_domain = DB::table('settings')->where('name','enable_domain')->where('value','on')->where('store_id',$domain->store_id)->first();
                    if($enable_domain){
                    $admin = DB::table('users')->find($enable_domain->created_by);
                        if($enable_domain->value == 'on' &&  $enable_domain->store_id == $admin->current_store){
                            $store = DB::table('stores')->find($admin->current_store);
                            
                            if($store){
                                \Config::set('view.paths',[resource_path('views'), base_path('themes/'.$store->theme_id.'/views')]);
                            }
                        }
                        else{
                            if(request()->segments())
                            {
                                $slug =request()->segments()[0];
                    
                                if($slug != 'admin' && $slug != 'logout' && $slug != 'install' && $slug != 'api')
                                {
                                    $store = DB::table('stores')->where('slug',$slug)->first();
                                    
                                    if($store)
                                    {
                                        \Config::set('view.paths',[
                                            resource_path('views'),
                                            base_path('themes/'.$store->theme_id.'/views')
                                        ]);
                                    }
                                }
                            } else {
                                if (isset($admin)) {
                                     $store = DB::table('stores')->where('id', $admin->current_store)->first();
                                    
                                    if($store)
                                    {
                                        \Config::set('view.paths',[
                                            resource_path('views'),
                                            base_path('themes/'.$store->theme_id.'/views')
                                        ]);
                                    }
                                }
                               
                            }
                        }
                    }
                }
            }
            else{
                if(request()->segments())
                {
                    $slug =request()->segments()[0];
        
                    if($slug != 'admin' && $slug != 'logout' && $slug != 'install' && $slug != 'api')
                    {
                        $store = DB::table('stores')->where('slug',$slug)->first();
                        
                        if($store)
                        {
                            \Config::set('view.paths',[
                                resource_path('views'),
                                base_path('themes/'.$store->theme_id.'/views')
                            ]);
                        }
                    }
                } else {
                                if (isset($admin)) {
                                     $store = DB::table('stores')->where('id', $admin->current_store)->first();
                                    
                                    if($store)
                                    {
                                        \Config::set('view.paths',[
                                            resource_path('views'),
                                            base_path('themes/'.$store->theme_id.'/views')
                                        ]);
                                    }
                                }
                            }
            }

        }

        // $store  = Store::where('slug',$slug)->first();


    }
}