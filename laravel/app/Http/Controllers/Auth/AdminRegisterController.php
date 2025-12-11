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
        // Ensure company is verified
        if (!$company->verified) {
            return redirect()
                ->route("register.company")
                ->withErrors("Company not verified.");
        }

        return view("auth.register-admin", compact("company"));
    }

    public function registerAdmin(Request $request, Company $company)
    {
        // Validation
        $data = $request->validate([
            "name" => "required|string|max:191",
            "email" => "required|email|max:191|unique:users,email",
            "password" => "required|string|min:6|confirmed",
        ]);

        // Create new admin
        $user = User::create([
            "company_id" => $company->id,
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
        ]);

        // Assign company-admin role
        $user->assignRole("company-admin");

        // Send verification email
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()
            ->route("login")
            ->with("status", "Admin registered. Verify your email.");
    }
}
