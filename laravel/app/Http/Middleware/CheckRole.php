<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has any of the required roles (roles are pipe-separated in route definition)
        foreach ($roles as $roleString) {
            // Handle pipe-separated roles like "super-admin|company-admin"
            $roleList = explode('|', $roleString);
            if ($user->hasRole($roleList)) {
                return $next($request);
            }
        }

        abort(403, "Unauthorized");
    }
}
