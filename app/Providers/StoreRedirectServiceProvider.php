<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StoreRedirectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::bind('storeSlug', function ($value) {
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
                                return redirect($remote.'/'.$store->slug.'/home');
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
                                        return redirect($remote.'/'.$store->slug.'/home');
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
                                return redirect($remote.'/'.$store->slug.'/home');
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
                                        return redirect($remote.'/'.$store->slug.'/home');
                                    }
                                }
                            } else {
                                if (isset($admin)) {
                                     $store = DB::table('stores')->where('id', $admin->current_store)->first();
                                    
                                    if($store)
                                    {
                                        return redirect($remote.'/'.$store->slug.'/home');
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
                            return redirect($remote.'/'.$store->slug.'/home');
                        }
                    }
                } else {
                                if (isset($admin)) {
                                     $store = DB::table('stores')->where('id', $admin->current_store)->first();
                                    
                                    if($store)
                                    {
                                        return redirect($remote.'/'.$store->slug.'/home');
                                    }
                                }
                            }
            }

            // If the store slug is not found, continue with the request
            return $value;
        });
    }
}
