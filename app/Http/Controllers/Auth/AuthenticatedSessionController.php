<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
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
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $settings = \App\Models\Utility::Seting();
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
        
        
        $user = User::where('email',$request->email)->first();
        if($user != null)
        {
            $companyUser = User::where('id' , $user->created_by)->first();
        }

        if($user != null && $user->is_active == 0 && $user->type != 'super admin')
        {
            return redirect()->back()->with('status', __('Your Account is de-activate,please contact your Administrator.'));
        }

        if(($user != null && $user->is_enable_login == 0 || (isset($companyUser) && $companyUser != null) && $companyUser->is_enable_login == 0)  && $user->type != 'super admin')
        {
            return redirect()->back()->with('status', __('Your Account is disable,please contact your Administrator.'));
        }

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showCustomerLoginForm($lang = '')
    {

        return view('auth.customer_login', compact('lang'));
    }
}
