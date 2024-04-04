<?php

namespace App\Http\Middleware;

use App\Models\{Plan, User};
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Cookie;

class SetLocate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $lang =  Cookie::get('LANGUAGE') ?? 'en';

        if(auth()->check())
        {
            if (empty($lang)) {
                $lang = Cookie::get('CURRENT_LANGUAGE');
                if (empty($lang)) {
                    $lang = auth()->user()->language;
                }
            }
        }else{
            $superadmin = User::where('type','super admin')->first();
            $lang = !empty($lang) ? $lang : $superadmin->default_language;
        }
        if(auth()->user())
        {
            $user = auth()->user();

            if($user->type == 'admin')
            {

                // if($plan)
                // {
                    $datetime1 = new \DateTime($user->plan_expire_date);
                    $datetime2 = new \DateTime(date('Y-m-d'));

                    $interval = $datetime2->diff($datetime1);
                    $days     = $interval->format('%r%a');

                    if($days <= 0)
                    {
                        $plan = Plan::where('duration','Unlimited')->first();

                        $user->assignPlan(1);

                        // return redirect()->route('plan.index')->with('error', __('Your Plan is expired.'));
                    }
                    if($user->trial_expire_date != null)
                    {
                        if(\Auth::user()->trial_expire_date < date('Y-m-d'))
                        {
                            $user->assignPlan(1);
                            // return redirect()->route('plan.index')->with('error', __('Your Trial plan Expired.'));
                            // return redirect()->intended(RouteServiceProvider::HOME)->with('error', __('Your Trial plan Expired.'));
                        }
                    }
                // }
            }
        }

        // $lang =  Cookie::get('LANGUAGE');
        // if (empty($lang)) {
        //     $lang = session()->get('lang');
        // }

        // if(auth()->check())
        // {
        //     if (empty($lang) || $lang == 'null') {
        //         $lang = auth()->user()->language;
        //     }
        // }else{
        //     $superadmin = User::where('type','super admin')->first();
        //     $lang = !empty($lang) ? $lang : $superadmin->default_language;
        // }

        App::setLocale($lang);
        return $next($request);
    }
}
