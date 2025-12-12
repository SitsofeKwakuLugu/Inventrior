<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        // Validate form inputs
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        // Attempt login
        if (Auth::attempt($credentials, $request->filled("remember"))) {

            // Prevent session fixation
            $request->session()->regenerate();

            $user = Auth::user();
            $userRole = $user->role ?? 'staff';

            // Redirect based on role
            if ($userRole === "super-admin") {
                return redirect()->route("superadmin.dashboard");
            }

            if ($userRole === "company-admin") {
                return redirect()->route("admin.dashboard");
            }

            return redirect()->route("staff.dashboard");
        }

        // Failed login
        return back()->withErrors([
            "email" => "Invalid credentials",
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("login");
    }
}
