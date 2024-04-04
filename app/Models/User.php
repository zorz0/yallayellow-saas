<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use DB;
use Carbon\Carbon;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Paddle\Billable;

class User extends Authenticatable implements LaratrustUser, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,HasRolesAndPermissions, Impersonate,Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'type',
        'email_verified_at',
        'mobile',
        'register_type',
        'is_assign_store',
        'current_store',
        'language',
        'default_language',
        'plan_id',
        'plan_expire_date',
        'plan_is_active',
        'requested_plan',
        'storage_limit',
        'is_active',
        'created_by',
        'theme_id',
        'remember_token',
        'is_enable_login'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static $defalut_theme = [
        'grocery',
        'babycare',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getProfileImageAttribute($value) {
        if (!empty($value)) {
            return 'storage/'.$value;
        } else {
            return null;
        }
        
    }

    public function creatorId()
    {
        if($this->type == 'admin' || $this->type == 'super admin')
        {
            return $this->id;
        }
        else
        {
            return $this->created_by;
        }
    }

    public static function slugs($data)
    {
        $slug = '';
        $slug = strtolower(str_replace(" ", "-",$data));
        $table = with(new Store)->getTable();
        $allSlugs = self::getRelatedSlugs($table, $slug ,$id = 0);

        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;

            }
        }
    }

    public function dateFormat($date)
    {
        $settings = Utility::seting();

        return date($settings['site_date_format'], strtotime($date));
    }


    protected static function getRelatedSlugs($table, $slug, $id = 0)
    {
        return DB::table($table)->select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'created_by', 'id');
    }

    public function countStore()
    {
        return Store::where('created_by', '=', $this->creatorId())->count();
    }
    public function assignPlan($planID)
    {
        $plan = Plan::find($planID);
        if($plan)
        {
            $this->plan_id = $plan->id;
            if($this->trial_expire_date != null);
            {
                $this->trial_expire_date = null;
            }
            if($plan->duration == 'Month')
            {
                $this->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            }
            elseif($plan->duration == 'Year')
            {
                $this->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            }
            else if($plan->duration == 'Unlimited')
            {
                $this->plan_expire_date = null;
            }

            $this->save();
            $users    = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'super admin')->get();
            // $products = Product::where('created_by', '=', \Auth::guard('admin')->user()->creatorId())->get();
            // $stores   = Store::where('created_by', '=', \Auth::guard('admin')->user()->creatorId())->get();

            // if($plan->max_stores == -1)
            // {
            //     foreach($stores as $store)
            //     {
            //         $store->is_active = 1;
            //         $store->save();
            //     }
            // }
            // else
            // {
            //     $storeCount = 0;
            //     foreach($stores as $store)
            //     {
            //         $storeCount++;
            //         if($storeCount <= $plan->max_stores)
            //         {
            //             $store->is_active = 1;
            //             $store->save();
            //         }
            //         else
            //         {
            //             $store->is_active = 0;
            //             $store->save();
            //         }
            //     }
            // }

            // if($plan->max_products == -1)
            // {
            //     foreach($products as $product)
            //     {
            //         $product->is_active = 1;
            //         $product->save();
            //     }
            // }
            // else
            // {
            //     $productCount = 0;
            //     foreach($products as $product)
            //     {
            //         $productCount++;
            //         if($productCount <= $plan->max_products)
            //         {
            //             $product->is_active = 1;
            //             $product->save();
            //         }
            //         else
            //         {
            //             $product->is_active = 0;
            //             $product->save();
            //         }
            //     }
            // }
            if($plan->max_users == -1)
            {
                foreach($users as $user)
                {
                    $user->is_active = 1;
                    $user->save();
                }
            }
            else
            {
                $userCount = 0;
                foreach($users as $user)
                {
                    $userCount++;
                    if($userCount <= $plan->max_users)
                    {
                        $user->is_active = 1;
                        $user->save();
                    }
                    else
                    {
                        $user->is_active = 0;
                        $user->save();
                    }
                }
            }
            return ['is_success' => true];
        }
        else
        {
            return [
                'is_success' => false,
                'error' => 'Plan is deleted.',
            ];
        }
    }

    public function currentPlan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

    public function countProducts()
    {
        return Product::where('created_by', '=', $this->creatorId())->count();
    }

    public function countCompany()
    {
        return User::where('type', '=', 'admin')->where('created_by', '=', $this->creatorId())->count();
    }

    // Relationship with users created by the user
    public function createdAdmins()
    {
        return $this->hasMany(User::class, 'created_by')->where('type', 'admin');
    }

    public static $superadmin_activated_module = [
        'ProductEnquiry',
    ];

    public function totalStoreUser($id)
    {
        return User::where('created_by', '=', $id)->count();
    }

    public function totalStoreCustomer($id)
    {
        return Customer::where('store_id', '=', $id)->count();
    }

    public function totalStoreVender($id)
    {
        //return Vender::where('created_by', '=', $id)->count();
        return 0;
    }
}
