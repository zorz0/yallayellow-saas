<?php

namespace Modules\LandingPage\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $permission  = [
            'Manage LandingPage',
            'Create LandingPage',
            'Edit LandingPage',
            'Store LandingPage',
            'Update LandingPage',
            'Delete LandingPage',
            'Manage Marketplace',
            'Create Marketplace',
            'Edit Marketplace',
            'Store Marketplace',
            'Update Marketplace',
            'Delete Marketplace',
        ];

        $company_role = Role::where('name','super admin')->first();
        foreach ($permissions as $value)
        {
            $permission = Permission::where('name',$value)->where('module','LandingPage')->first();
            if(!$permission)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'LandingPage',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                
            }

            if($company_role && !$company_role->hasPermission($value))
            {
                $company_role->givePermission($value);
            } else {
                $company_role = Role::create(
                    [
                        'name' => 'super admin',
                        'created_by' => 0,
                    ]
                );

                if($company_role && !$company_role->hasPermission($value))
                {
                    $company_role->givePermission($value);
                }
            }
        }

    }

}
