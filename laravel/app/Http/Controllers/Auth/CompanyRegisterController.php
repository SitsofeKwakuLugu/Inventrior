<?php
namespace App\Http\Controllers\Auth;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyVerificationMail;

class CompanyRegisterController extends Controller
{
    public function showCompanyForm() { return view("auth.register-company"); }

    public function registerCompany(Request $request)
    {
        $data = $request->validate([
            "name"=>"required|string|max:191",
            "email"=>"required|email|max:191",
            "address"=>"nullable|string",
            "phone"=>"nullable|string"
        ]);

        $company = Company::create([
            "name"=>$data["name"],
            "email"=>$data["email"],
            "address"=>$data["address"] ?? null,
            "phone"=>$data["phone"] ?? null,
            "code"=>strtoupper(substr(Str::uuid()->toString(),0,8)),
            "verified"=>false
        ]);

        $signedUrl = URL::signedRoute("company.verify", ["company"=>$company->id], now()->addHours(72));
        Mail::to($company->email)->send(new CompanyVerificationMail($company,$signedUrl));

        return redirect()->route("register.company")->with("status","Company registered. Check email for verification.");
    }
}