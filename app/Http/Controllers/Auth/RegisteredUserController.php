<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use App\Models\Plan;
use App\Models\Setting;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(Request $request)
    {
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $settings = \App\Models\Utility::Seting();
        if($settings['email_verification'] == "on")
        {
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
            if (isset($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes') {
                // Validate captcha
                $captchaResponse = $request->input('g-recaptcha-response');
                if (empty($captchaResponse)) {
                    return redirect()->back()->with('status', __('Please checked RECAPTCHA.'));
                }
                $captchaSecretKey = $settings['NOCAPTCHA_SECRET'] ?? null;
                $response = \Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $captchaSecretKey,
                    'response' => $captchaResponse,
                ]);
                $captchaResult = json_decode($response->body());
    
                if (!$captchaResult->success) {
                    return redirect()->back()->with('status', __('RECAPTCHA Captcha validation failed.'));
                }
            }

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'store_name' => ['required', 'string', 'max:255'],
            ]);
            $superAdmin = User::where('type','super admin')->first();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'profile_image' => 'uploads/profile/avatar.png',
                'type' => 'admin',
                //'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make($request->password),
                'mobile' => '',
                'default_language' => $superAdmin->default_language ?? 'en',
                'created_by' => 1,
                'theme_id' => 'grocery',
            ]);

            $slug = User::slugs($request->store_name);

            $store = Store::create([
                    'name' => $request->store_name,
                    'email' => $request->email,
                    'theme_id' => $user->theme_id,
                    'slug' => $slug,
                    'created_by' => $user->id,
                    'default_language' => $superAdmin->default_language ?? 'en'
                ]);

            $user->current_store = $store->id;
            $user->save();

            if($user->type == 'admin')
            {
                $plan = Plan::find($user->plan_id);
                if($plan)
                {
                    if($plan->duration != 'Unlimited')
                    {
                        $datetime1 = new \DateTime($user->plan_expire_date);
                        $datetime2 = new \DateTime(date('Y-m-d'));
                        $interval = $datetime2->diff($datetime1);
                        $days     = $interval->format('%r%a');
                        if($days <= 0)
                        {
                            $user->assignPlan(1);

                            return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Plan is expired.'));
                        }
                    }

                    if($user->trial_expire_date != null)
                    {
                        if(\Auth::user()->trial_expire_date < date('Y-m-d'))
                        {
                            $user->assignPlan(1);

                            return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Trial plan Expired.'));
                        }
                    }
                }
            }

            $role_r = Role::where('name', 'admin')->first();
            $user->addRole($role_r);

            try {
               
                event(new Registered($user));
                Auth::login($user);

            } catch (\Exception $e) {
                dd($e);
                $user->delete();

                // return redirect('/register')->with('status', __('Email SMTP settings does not configure so please contact to your site admin.'));
            }
            return redirect()->route('verify-email');
        } else {
            if (isset($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes') {
                // Validate captcha
                $captchaResponse = $request->input('g-recaptcha-response');
                if (empty($captchaResponse)) {
                    return redirect()->back()->with('status', __('Please checked RECAPTCHA.'));
                }
                $captchaSecretKey = $settings['NOCAPTCHA_SECRET'] ?? null;
                $response = \Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $captchaSecretKey,
                    'response' => $captchaResponse,
                ]);
                $captchaResult = json_decode($response->body());
    
                if (!$captchaResult->success) {
                    return redirect()->back()->with('status', __('RECAPTCHA Captcha validation failed.'));
                }
            }

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'store_name' => ['required', 'string', 'max:255'],
            ]);
            $superAdmin = User::where('type','super admin')->first();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'profile_image' => 'uploads/profile/avatar.png',
                'type' => 'admin',
               // 'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make($request->password),
                'mobile' => '',
                'default_language' => $superAdmin->default_language ?? 'en',
                'created_by' => 1,
                'theme_id' => 'grocery',
            ]);

            $slug = User::slugs($request->store_name);

            $store = Store::create([
                    'name' => $request->store_name,
                    'email' => $request->email,
                    'theme_id' => $user->theme_id,
                    'slug' => $slug,
                    'created_by' => $user->id,
                    'default_language' => $superAdmin->default_language ?? 'en'
                ]);

            $user->current_store = $store->id;
            $user->save();

            if($user->type == 'admin')
            {
                $plan = Plan::find($user->plan_id);
                if($plan)
                {
                    if($plan->duration != 'Unlimited')
                    {
                        $datetime1 = new \DateTime($user->plan_expire_date);
                        $datetime2 = new \DateTime(date('Y-m-d'));
                        $interval = $datetime2->diff($datetime1);
                        $days     = $interval->format('%r%a');
                        if($days <= 0)
                        {
                            $user->assignPlan(1);

                            return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Plan is expired.'));
                        }
                    }

                    if($user->trial_expire_date != null)
                    {
                        if(\Auth::user()->trial_expire_date < date('Y-m-d'))
                        {
                            $user->assignPlan(1);

                            return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Trial plan Expired.'));
                        }
                    }
                }
            }

            $role_r = Role::where('name', 'admin')->first();
            $user->addRole($role_r);

            Auth::login($user);
              $setting = new Setting();

     
        $setting->name = 'is_cod_enabled';
        $setting->value = 'on';
        $setting->created_by =  auth()->user()->id;
        $setting->theme_id = APP_THEME();
        $setting->store_id = $store->id;
       
        
        // Save the record to the database
        $setting->save();
            return redirect(RouteServiceProvider::FORMPAGE);
        }
    }

    public function verify_email()
    {
        return view('auth.verify-email');
    }
}
