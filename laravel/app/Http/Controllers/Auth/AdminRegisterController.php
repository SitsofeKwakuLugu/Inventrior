<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends Controller
{
    public function showAdminForm(Request $request, Company $company)
    {
        return view("auth.register-admin", compact("company"));
    }

    public function registerAdmin(Request $request, Company $company)
    {
        // Validation
        $data = $request->validate([
            "name" => "required|string|min:3|max:191",
            "email" => "required|email|max:191|unique:users,email",
            "password" => "required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/|confirmed",
        ], [
            'name.required' => 'Full name is required',
            'name.min' => 'Name must be at least 3 characters',
            'email.required' => 'Email is required',
            'email.unique' => 'This email is already in use',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character (@$!%*?&)',
            'password.confirmed' => 'Passwords do not match'
        ]);

        // Create new admin
        $user = User::create([
            "company_id" => $company->id,
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
            "email_verified_at" => now()
        ]);

        // Assign company-admin role
        $user->assignRole("company-admin");

        return redirect()
            ->route("login")
            ->with("success", "Admin registered successfully! You can now log in.");
    }
}
