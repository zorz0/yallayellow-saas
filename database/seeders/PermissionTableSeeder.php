<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Role;
use App\Models\{Permission, Utility};
use Illuminate\Support\Facades\Hash;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('cache:clear');

        // Super Admin
        $superadmin = User::where('type','super admin')->first();
        if(empty($superadmin))
        {
            $superadmin = new User();
            $superadmin->name = 'Super Admin';
            $superadmin->email = 'superadmin@example.com';
            $superadmin->profile_image = 'uploads/profile/avatar.png';
            $superadmin->type = 'super admin';
            $superadmin->password = Hash::make('1234');
            $superadmin->mobile = '7878787878';
            $superadmin->default_language = 'en';
            $superadmin->theme_id = 'grocery';
            $superadmin->email_verified_at = date('Y-m-d H:i:s');
            $superadmin->save();

            $slug = User::slugs('my-store');
            $superAdminStore = Store::create([
                'name' => 'my-store',
                'email' => $superadmin->email,
                'theme_id' => $superadmin->theme_id,
                'slug' => $slug,
                'created_by' => $superadmin->id,
                'default_language' => $superadmin->default_language ?? 'en'
            ]);

            $superadmin->current_store = $superAdminStore->id;
            $superadmin->save();

            // create Super Admin Default Setting
            defaultSetting('grocery', $superAdminStore->id, 'super admin', $superadmin);

            $role = Role::where('name','super admin')->where('guard_name','web')->exists();
            if(!$role)
            {
                $superAdminRole        = Role::create(
                    [
                        'name' => 'super admin',
                        'created_by' => 0,
                    ]
                );
            }
            $role_r = Role::where('name','super admin')->first();
            $superadmin->addRole($role_r);
        }

        $superAdminRole  = Role::where('name','super admin')->first();
        
        // company
        $company = User::where('type','admin')->first();
        if(empty($company))
        {
            $company = new User();
            $company->name = 'Admin';
            $company->email = 'admin@example.com';
            $company->profile_image = 'uploads/profile/avatar.png';
            $company->type = 'admin';
            $company->password = Hash::make('1234');
            $company->mobile = '7878787878';
            $company->default_language = $superadmin->default_language ?? 'en';
            $company->created_by = $superadmin->id;
            $company->theme_id = 'grocery';
            $company->plan_id = '1';
            $company->email_verified_at = date('Y-m-d H:i:s');
            $company->save();

            $slug = User::slugs('grocery');
            $store = Store::create([
                'name' => 'grocery',
                'email' => $company->email,
                'theme_id' => $company->theme_id,
                'slug' => $slug,
                'created_by' => $company->id,
                'default_language' => $company->default_language ?? 'en'
            ]);

            $company->current_store = $store->id;
            $plan = Plan::where('name', 'Free Plan')->first();
            if (isset($plan) && $plan->themes) {
                $themes = explode(',', $plan->themes);
                foreach ($themes as $theme) {
                    themeDefaultSection($theme, $store->id);
                }
            }
            $company->plan_id = $plan->id ?? null;
            $company->save();

            // create Admin Default Setting
            defaultSetting('grocery', $store->id, 'admin', $company);

            $admin_role = Role::where('name','admin')->where('guard_name','web')->exists();
            if(!$admin_role)
            {
                $AdminRole        = Role::create(
                    [
                        'name' => 'admin',
                        'created_by' => $superadmin->id,
                    ]
                );
            }
            $role_admin = Role::where('name','admin')->first();
            $company->addRole($role_admin);
        }

        $admin_role = Role::where('name','admin')->first();
        

        Utility::addNewData();
        Utility::country_insert();
        Utility::state_insert();
        Utility::city_insert();       
       

        $company = User::where('type','admin')->first();
        try{

            $assigned_role = $company->roles->first();
        }catch(\Exception $e){
            $assigned_role = null;
        }
        if(!$assigned_role && !empty($company))
        {
            $company->addRole($admin_role);
        }

    }
}
