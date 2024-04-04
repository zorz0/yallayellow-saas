<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\App;
use App\Models\User;

class ThemeLanguage
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
        $currantLang = Cookie::get('LANGUAGE');
        if($currantLang == null){
            $user = \Auth::user();
            if($user){
                $currantLang = $user->language;
            }
            if($currantLang == null){
                $user = User::where('type','admin')->first();
                $currantLang = $user->language;
            }
        }

        \App::setLocale($currantLang);

        return $next($request);
    }
}
