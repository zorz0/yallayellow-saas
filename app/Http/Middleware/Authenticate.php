<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }

        return $request->expectsJson() ? null : route('login');
    }
}
