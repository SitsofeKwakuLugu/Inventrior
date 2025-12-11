<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCompanyAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Redirect if not logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Allow super-admin
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // TODO: Add company access check here if needed
        return $next($request);
    }
}
