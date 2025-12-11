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
            "name"=>"required|string|min:3|max:191",
            "email"=>"required|email|max:191|unique:companies,email",
            "address"=>"nullable|string|max:255",
            "phone"=>"nullable|string|regex:/^[0-9\-\+\(\)\s]{7,20}$/"
        ], [
            'name.required' => 'Company name is required',
            'name.min' => 'Company name must be at least 3 characters',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'phone.regex' => 'Please enter a valid phone number'
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

        return redirect()->route("register.company")->with("success","Company registered! Check your email for verification link (valid for 72 hours).");
    }
}