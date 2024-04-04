<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class XSS
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {          
            $migrations             = $this->getMigrations();
            $dbMigrations           = $this->getExecutedMigrations();
            $numberOfUpdatesPending = count($migrations) - count($dbMigrations);

            // dd($migrations, $dbMigrations);

            if($numberOfUpdatesPending > 0)
            {
                return redirect()->route('LaravelUpdater::welcome');
            }
        }

        $input = $request->all();
        $request->merge($input);
        return $next($request);
    }
}
