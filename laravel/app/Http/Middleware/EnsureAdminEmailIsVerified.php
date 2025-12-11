<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureAdminEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) return redirect()->route("login");
        if ($user->hasRole("super-admin")) return $next($request);
        if ($user->hasRole("company-admin") && !$user->hasVerifiedEmail()) {
            return redirect()->route("verification.notice")
                ->with("status","Please verify your email to access the admin dashboard.");
        }
        return $next($request);
    }
}