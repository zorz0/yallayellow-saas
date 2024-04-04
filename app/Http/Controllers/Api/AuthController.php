<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Customer;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendOTP;
use App\Mail\OTPVerify;
use App\Models\Coupon;
use App\Models\DeliveryAddress;
use App\Models\UserAdditionalDetail;
use App\Models\Utility; 
use App\Models\Store;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    use ApiResponser;

    // public function __construct(Request $request)
    // {
    //     if (request()->segments()) {
    //         $slug = request()->segments()[1];
    //         $this->store = Store::where('slug',$slug)->first();
    //         $this->APP_THEME = $this->store->theme_id;
    //     }
    // }

    public function register(Request $request,$slug)
    {
        $store = Store::where('slug',$slug)->first();

        $rules = [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'token' => 'nullable',
            'device_type' => 'nullable',
        ];

        $email = $request->email;
        $request->register_type = !empty($request->register_type) ? $request->register_type : 'email';
        $theme_id = !empty($store) ? $store->theme_id : $request->theme_id;

        if( $request->register_type == 'email' ) {
            $rules['email'] = 'required|string|email|unique:customers,email';
        } elseif(empty($request->email)) {
            $email = $request->email = $request->first_name.rand(0,1000).'@example.com';
        }

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ],$messages->first());
        }

        $user                   = new Customer();
        if(!empty($request->email) && $request->register_type != 'email') {
            if( $request->register_type == 'facebook') {

                if(empty($email) && empty($request->mobile)) {
                    return $this->error(['message' => __('Email or mobile required in facebook login.')], __('Email or mobile required in facebook login.'));
                }

                $user_query = Customer::query();
                if(!empty($email)) {
                    $user = $user_query->where('email', $email)->first();
                }
                if(!empty($email)) {
                    $user = $user_query->where('mobile', $request->mobile)->first();
                }
            } else {
                $user = Customer::where('email', $request->email)->first();
            }

            if(empty($user)) {
                $user                   = new Customer();
            }
        }

        $user->first_name       = $request->first_name ?? null;
        $user->last_name        = $request->last_name ?? null;
        $user->email            = $request->email ?? null;
        $user->type             = 'customer';
        $user->mobile           = $request->mobile ?? null;
        $user->firebase_token   = $request->token ?? null;
        $user->device_type      = $request->device_type ?? null;
        $user->register_type    = $request->register_type ?? null;
        $user->theme_id         = $theme_id;
        $user->created_by       = $store->created_by;
        $user->store_id         = $store->id;


        if ($request->register_type == 'email') {
            $rules = [
                'password' => 'required|string|min:6',
                'mobile' => 'required',
            ];
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return $this->error(['message' => $messages->first()], $messages->first());
            }
            $user->password         = bcrypt($request->password);
			$user->save();
        } else {
            $flag = 0;

            if ($request->register_type == 'google' && !empty($request->google_id) ) {
                $user->google_id       = $request->google_id;
                $flag = 1;
            }
            if ($request->register_type == 'apple' && !empty($request->apple_id) ) {
                $user->apple_id        = $request->apple_id;
                $flag = 1;
            }
            if ($request->register_type == 'facebook' && !empty($request->facebook_id) ) {
                $user->facebook_id     = $request->facebook_id;
                $flag = 1;
            }

            if ($flag == 0) {
                $message = $request->register_type . ' id is missing.';
                return $this->error(['message' => $message], $message);
            }
			$user->save();
        }

        auth('customers')->login($user);
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;
        $user->token_type = 'Bearer';

        $user_array = $user->toArray();
        $user_data = User::find($user->id);
        $user_array['image'] = !empty($user_data->profile_image) ? $user_data->profile_image : "themes/style/uploads/require/user.png";
        return $this->success($user_array, __('Register successfully.'));
    }

    public function login(Request $request,$slug='')
    {
        $store = Store::where('slug',$slug)->first();
        $theme_id = !empty($store) ? $store->theme_id  : $request->theme_id;

        $rules = [
            'email' => 'nullable|email',
            'token' => 'nullable',
            'device_type' => 'nullable',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ], $messages->first());
        }

        if (!empty($request->password)) {
           
            $user = Auth::guard('customers')->attempt(['email' => $request->email, 'password' => $request->password]);
            if (!$user) {
                return $this->error(['message' => __('Invalid login details')],__('Invalid login details'));
            }
            $user = auth('customers')->user();
            $user->firebase_token = $request->token;
            $user->save();

        } elseif (!empty($request->google_id) || !empty($request->facebook_id) || !empty($request->apple_id)) {

            //$User_query = Customer::where('email', $request->email);
            $User_query = Customer::query();
            if (!empty($request->google_id)) {
                $User_query->where('google_id', $request->google_id);
            } elseif (!empty($request->facebook_id)) {
                $User_query->where('facebook_id', $request->facebook_id);
            } elseif (!empty($request->apple_id)) {
                $User_query->where('apple_id', $request->apple_id);
            }

            $user = $User_query->first();

            if (!empty($user)) {
                $user->firebase_token = $request->token;
                $user->save();
            } else {
                return $this->error(['message' => __('Invalid login details.')], __('Invalid login details.'));
            }
        } else {
            return $this->error(['message' => __('Invalid login details')], __('Invalid login details'));
        }

        // Auth::loginUsingId(1)

        $user_data = Customer::find($user->id);
        $DeliveryAddress = DeliveryAddress::where('customer_id', $user->id)->where('default_address', 1)->first();

        $user_array['id'] = $user_data->id;
        $user_array['first_name'] = $user_data->first_name;
        $user_array['last_name'] = $user_data->last_name;
        $user_array['image'] = !empty($user_data->profile_image) ? $user_data->profile_image : "themes/style/uploads/require/user.png";
        $user_array['name'] = $user_data->name;
        $user_array['email'] = $user_data->email;
        $user_array['mobile'] = $user_data->mobile;
        $user_array['company_name'] = !empty($DeliveryAddress->company_name) ? $DeliveryAddress->company_name : '';
        $user_array['country_id'] = !empty($DeliveryAddress->country_id) ? $DeliveryAddress->country_id : '';
        $user_array['state_id'] = !empty($DeliveryAddress->state_id) ? $DeliveryAddress->state_id : '';
        $user_array['city'] = !empty($DeliveryAddress->city) ? $DeliveryAddress->city : '';
        $user_array['address'] = !empty($DeliveryAddress->address) ? $DeliveryAddress->address : '';
        $user_array['postcode'] = !empty($DeliveryAddress->postcode) ? $DeliveryAddress->postcode : '';

        auth('customers')->login($user);
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user_array['token'] = $token;
        $user_array['token_type'] = 'Bearer';
        return $this->success($user_array, __('Login successfully.'));
    }

    public function logout(Request $request,$slug='')
    {
        // $store = Store::where('slug',$slug)->first();
        // $theme_id = !empty($store) ? $store->theme_id  : $request->theme_id;

        $user = auth()->user();

        if (!empty($user)) {
            return $this->success([
                'message' => __('User Logout.'),
                'logout' => $user->tokens()->delete()
            ], __('User Logout'));
        } else {
            return $this->error([
                'message' => 'User not found'
            ], __('User not found'));
        }
    }

    public function forgot_password_send_otp(Request $request,$slug='')
    {

        $store = Store::where('slug',$slug)->first();
        $theme_id = !empty($store) ? $store->theme_id  : $request->theme_id;

        $email = $request->email;
        $has_email = Customer::where('email', $email)->first();
        if(empty($has_email)) {
            return $this->error([
                'message' => "We can't find a user with that email address."
            ], __("We can't find a user with that email address."));
        }

        if($has_email->register_type != 'email') {
            return $this->error([
                'message' => "You can't login because you used social login."
            ], __("You can't login because you used social login."));
        }

        $OTP = Utility::generateNumericOTP(4);
        $has_email->update(['password_otp' => $OTP,'password_otp_datetime' => date('Y-m-d H:i:s') ]);

        $settings = Setting::where('theme_id', $theme_id)->where('store_id',$store->id)->pluck('value', 'name')->toArray();

        try {
            config(
                [
                    'mail.driver' => $settings['MAIL_DRIVER'],
                    'mail.host' => $settings['MAIL_HOST'],
                    'mail.port' => $settings['MAIL_PORT'],
                    'mail.encryption' => $settings['MAIL_ENCRYPTION'],
                    'mail.username' => $settings['MAIL_USERNAME'],
                    'mail.password' => $settings['MAIL_PASSWORD'],
                    'mail.from.address' => $settings['MAIL_FROM_ADDRESS'],
                    'mail.from.name' => $settings['MAIL_FROM_NAME'],
                ]
            );

            $email = $has_email->email;
            // $email = 'jehegek554@bongcs.com';
            Mail::to($email)->send(new SendOTP($OTP));
            return $this->success([
                'message' => 'We have emailed your OTP!.',
                'infomation' => 'OTP is valid for 10 minutes.'
            ], __("We have emailed your OTP!."));
        } catch (\Throwable $th) {
            return $this->error([
                'message' => 'E-Mail has been not sent due to SMTP configuration.'
            ], __("E-Mail has been not sent due to SMTP configuration."));
        }
    }

    public function forgot_password_verify_otp(Request $request,$slug='')
    {

        $store = Store::where('slug',$slug)->first();
        $theme_id = !empty($store) ? $store->theme_id  : $request->theme_id;

        $email = $request->email;
        $request_otp = $request->otp;

        $has_email = Customer::where('email', $email)->first();
        if(empty($has_email)) {
            return $this->error([
                'message' => "We can't find a user with that email address."
            ], __("We can't find a user with that email address."));
        }

        //$otp = UserAdditionalDetail::where('user_id', $has_email->id)->first();
        if(empty($has_email->password_otp)) {
            return $this->error([
                'message' => "OTP no found."
            ], __("OTP no found"));
        }

        $expire_time = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($has_email->password_otp_datetime)));
        $current_time = date('Y-m-d H:i:s');
        if($expire_time < $current_time) {
            return $this->error([
                'message' => "OTP has been expired."
            ], __("OTP has been expired."));
        }

        if($request_otp != $has_email->password_otp) {
            return $this->error([
                'message' => "OTP has been not matched."
            ], __("OTP has been not matched."));
        }

        $has_email->password_otp = null;
        $has_email->password_otp_datetime = null;
        $password = 'hello@1234';
        $has_email->password = bcrypt($password);
        $has_email->save();

        $settings = Setting::where('theme_id', $theme_id)->where('store_id',$store->id)->pluck('value', 'name')->toArray();

        config(
            [
                'mail.driver' => $settings['MAIL_DRIVER'],
                'mail.host' => $settings['MAIL_HOST'],
                'mail.port' => $settings['MAIL_PORT'],
                'mail.encryption' => $settings['MAIL_ENCRYPTION'],
                'mail.username' => $settings['MAIL_USERNAME'],
                'mail.password' => $settings['MAIL_PASSWORD'],
                'mail.from.address' => $settings['MAIL_FROM_ADDRESS'],
                'mail.from.name' => $settings['MAIL_FROM_NAME'],
            ]
        );
        Mail::to($has_email->email)->send(new OTPVerify($has_email, $password));

        $DeliveryAddress = DeliveryAddress::where('customer_id', $has_email->id)->where('default_address', 1)->first();
        $user_array = [];
        $user_array['id'] = $has_email->id;
        $user_array['first_name'] = $has_email->first_name;
        $user_array['last_name'] = $has_email->last_name;
        $user_array['image'] = !empty($has_email->profile_image) ? $has_email->profile_image : "themes/style/uploads/require/user.png";
        $user_array['name'] = $has_email->name;
        $user_array['email'] = $has_email->email;
        $user_array['mobile'] = $has_email->mobile;
        $user_array['company_name'] = !empty($DeliveryAddress->company_name) ? $DeliveryAddress->company_name : '';
        $user_array['country_id'] = !empty($DeliveryAddress->country_id) ? $DeliveryAddress->country_id : '';
        $user_array['state_id'] = !empty($DeliveryAddress->state_id) ? $DeliveryAddress->state_id : '';
        $user_array['city'] = !empty($DeliveryAddress->city) ? $DeliveryAddress->city : '';
        $user_array['address'] = !empty($DeliveryAddress->address) ? $DeliveryAddress->address : '';
        $user_array['postcode'] = !empty($DeliveryAddress->postcode) ? $DeliveryAddress->postcode : '';
        $token = $has_email->createToken('auth_token')->plainTextToken;
        $user_array['token'] = $token;
        $user_array['token_type'] = 'Bearer';
        $user_array['message'] = "OTP has been successfully matched.";
        return $this->success($user_array, __("OTP has been successfully matched."));
    }

    public function forgot_password_save(Request $request,$slug='')
    {
        $store = Store::where('slug',$slug)->first();
        $theme_id = !empty($store) ? $store->theme_id  : $request->theme_id;

        $email      = $request->email;
        $password   = $request->password;

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return $this->error([
                'message' => $messages->first()
            ], $messages->first());
        }

        $email = $request->email;
        $has_email = User::where('email', $email)->first();
        if(empty($has_email)) {
            return $this->error([
                'message' => "We can't find a user with that email address."
            ], __("We can't find a user with that email address."));
        }

        $has_email->password = bcrypt($request->password);
        $has_email->save();
        return $this->success([
            'message' => "Password changed successfully."
        ], __("Password changed successfully."));

    }
}
