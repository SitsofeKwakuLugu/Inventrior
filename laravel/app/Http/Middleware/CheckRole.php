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
        $userRole = $user->role ?? 'staff';
        
        foreach ($roles as $roleString) {
            // Handle pipe-separated roles like "super-admin|company-admin"
            $roleList = explode('|', $roleString);
            if (in_array($userRole, $roleList, true)) {
                return $next($request);
            }
        }

        abort(403, "Unauthorized");
    }
}
