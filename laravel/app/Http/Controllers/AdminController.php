<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Show all admins for the current company
     */
    public function index()
    {
        $company = auth()->user()->company;
        $admins = User::where('company_id', $company->id)
            ->where('role', 'company-admin')
            ->paginate(10);
        
        return view('dashboard.admin.admins.index', compact('admins', 'company'));
    }

    /**
     * Show admin creation form
     */
    public function create()
    {
        return view('dashboard.admin.admins.create');
    }

    /**
     * Store new admin
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:191',
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email')
            ],
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ], [
            'name.required' => 'Admin name is required',
            'name.min' => 'Name must be at least 3 characters',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character (@$!%*?&)',
        ]);

        $user = User::create([
            'company_id' => auth()->user()->company_id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now()
        ]);

        $user->assignRole('company-admin');

        return redirect()->route('admin.index')
            ->with('success', "Admin '{$user->name}' created successfully!");
    }

    /**
     * Edit admin form
     */
    public function edit(User $admin)
    {
        $this->authorize('update', $admin);
        return view('dashboard.admin.admins.edit', compact('admin'));
    }

    /**
     * Update admin
     */
    public function update(Request $request, User $admin)
    {
        $this->authorize('update', $admin);

        $data = $request->validate([
            'name' => 'required|string|min:3|max:191',
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($admin->id),
            ],
        ]);

        $admin->update($data);

        return redirect()->route('admin.index')
            ->with('success', "Admin '{$admin->name}' updated successfully!");
    }

    /**
     * Delete admin
     */
    public function destroy(User $admin)
    {
        $this->authorize('delete', $admin);
        
        // Prevent deleting the last admin
        $adminCount = User::where('company_id', $admin->company_id)
            ->where('role', 'company-admin')
            ->count();
        
        if ($adminCount <= 1) {
            return redirect()->route('admin.index')
                ->withErrors('Cannot delete the last admin. Assign another admin first.');
        }
        
        $name = $admin->name;
        $admin->delete();

        return redirect()->route('admin.index')
            ->with('success', "Admin '{$name}' deleted successfully!");
    }
}
