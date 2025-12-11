<?php

namespace App\Http\Controllers\Auth;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

class CompanyVerifyController extends Controller
{
    public function verify(Request $request, Company $company)
    {
        // If already verified
        if ($company->verified) {
            return redirect()
                ->route("login")
                ->with("status", "Company already verified.");
        }

        // Mark company as verified
        $company->update(['verified' => true]);

        // Create signed URL for admin registration (48 hours)
        $signedAdminUrl = URL::temporarySignedRoute(
            'register.admin',
            now()->addHours(48),
            ['company' => $company->id]
        );

        return redirect($signedAdminUrl)
            ->with("status", "Company verified. Please register the admin.");
    }
}
