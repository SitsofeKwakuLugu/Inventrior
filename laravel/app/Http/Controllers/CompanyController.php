<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Show company edit form
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update company
     */
    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:191',
            'email' => 'required|email|max:191|unique:companies,email,' . $company->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $company->update($data);

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Company '{$company->name}' updated successfully!");
    }

    /**
     * Delete company
     */
    public function destroy(Company $company)
    {
        // Prevent deletion if company has active users (except soft deleted)
        $activeUsers = $company->users()->count();
        
        if ($activeUsers > 0) {
            return redirect()->route('superadmin.dashboard')
                ->withErrors("Cannot delete company with active users. Please remove/deactivate all users first.");
        }

        $name = $company->name;
        $company->delete();

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Company '{$name}' deleted successfully!");
    }
}
