<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Paddle\Billable;
class Customer extends Authenticatable implements CanResetPasswordContract
{

    use Notifiable, CanResetPassword,HasApiTokens,Billable;

    protected $guard = 'customers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_image',
        'type',
        'email_verified_at',
        'mobile',
        'register_type',
        'regiester_date',
        'last_active',
        'status',
        'date_of_birth',
        'firebase_token',
        'device_type',
        'google_id',
        'apple_id',
        'facebook_id',
        'password_otp',
        'password_otp_datetime',
        'theme_id',
        'store_id',
        'remember_token',
        'created_by'
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // *********************************
    protected $appends = ["demo_field", "name", "address", "postcode"];

    public function getDemoFieldAttribute()
    {
        return 'demo_field';
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAddressAttribute()
    {
        $address  = '';
        if(!empty(auth()->user())) {
            $DeliveryAddress = DeliveryAddress::where('customer_id', auth('customers')->user()->id)->where('default_address', 1)->first();
            if(!empty($DeliveryAddress)) {
                $address = $DeliveryAddress->address;
            } else {
                $DeliveryAddress = DeliveryAddress::where('customer_id', auth('customers')->user()->id)->where('title', 'main')->first();
                if(!empty($DeliveryAddress)) {
                    $address = $DeliveryAddress->address;
                }
            }
        }
        return $address;
    }

    public function getPostcodeAttribute()
    {
        $address = '';
        if(!empty(auth()->user())) {
            $DeliveryAddress = DeliveryAddress::where('customer_id', auth('customers')->user()->id)->where('default_address', 1)->first();
            $address  = '';
            if(!empty($DeliveryAddress)) {
                $address = $DeliveryAddress->postcode;
            } else {
                $DeliveryAddress = DeliveryAddress::where('customer_id', auth('customers')->user()->id)->where('title',
                'main')->first();
                if(!empty($DeliveryAddress)) {
                    $address = $DeliveryAddress->postcode;
                }
            }
        }
        return $address;
    }

    // *********************************

    public function UserAdditionalDetail()
    {
        return $this->hasOne(UserAdditionalDetail::class, 'user_id',
        'id');
    }

    public static function dateFormat($date)
    {
        $settings = Utility::GetValueByName();

        return date($settings['site_date_format'], strtotime($date));
    }



    protected static function getRelatedSlugs($table, $slug, $id = 0)
    {
        return DB::table($table)->select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }

    public function creatorId()
    {
        if($this->type == 'admin' || $this->type == 'superadmin')
        {
            return $this->id;
        }
        else
        {
            return $this->created_by;
        }
    }

    public function Ordercount()
    {
        $users_id = Order::where('customer_id', $this->id)->get();
        if($users_id->count() == 0){
            $orders_detail = OrderBillingDetail::where('email',$this->email)->get()->toArray();
            $order_id = [];
            foreach($orders_detail as $orders){
                $order_id[] = $orders['order_id'];

            }
            $orders = Order::whereIn('id', $order_id)->where('customer_id', 0)->count();
        }
        else{
            $orders = Order::where('customer_id', $this->id)->count();

        }
        return $orders;
    }

    public function sendPasswordResetNotification($token)
    {
        $url = url("/{$this->storeSlug}/reset-password?token={$token}&email={$this->email}");
        // dd($url);
       // $url = route('customer.password.reset', ['storeSlug' => $this->storeSlug, 'token' => $token, 'email' => $this->email], false);

        $this->notify(new ResetPasswordNotification($url));
    }

    public function getaddress()
    {
        return $this->hasOne(DeliveryAddress::class, 'user_id', 'id');
    }

    public function total_spend(){
        $users_id = Order::where('customer_id', $this->id)->get();
        if($users_id->count() == 0){
            $orders_detail = OrderBillingDetail::where('email',$this->email)->get()->toArray();
            $order_id = [];
            foreach($orders_detail as $orders){
                $order_id[] = $orders['order_id'];

            }
            $orders = Order::whereIn('id',$order_id)->where('customer_id', 0)->get();
            $total_spend = $orders->sum('final_price');

        }else{
            $orders = Order::where('customer_id',$this->id)->get();
            $total_spend = $orders->sum('final_price');
        }
        return $total_spend;
    }

    public function getTotal()
    {

        $total = $this->last_active;

        return $total;
    }

    public static function customer_field()
    {
        $fields = [
            'Name' => 'Name',
            'Email' => 'Email',
            'Last active' => 'Last active',
            'AOV' => 'AOV',
            'No. of Orders' => 'No. of Orders',
            'Total Spend' => 'Total Spend',
        ];
        return $fields;
    }

    public static $fields_status = [
        'Includes',
        'Excludes',
    ];

    public static $fields_status1 = [
        'Before',
        'After',
        'Equal',
    ];

    public static $fields_status2 = [
        'Less Than',
        'More Than',
        'Equal',
    ];
}
