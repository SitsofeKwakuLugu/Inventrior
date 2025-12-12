<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Show all staff for the current company
     */
    public function index()
    {
        $company = auth()->user()->company;
        $staff = User::where('company_id', $company->id)
            ->where('role', 'staff')
            ->paginate(10);
        
        return view('dashboard.admin.staff.index', compact('staff', 'company'));
    }

    /**
     * Show staff creation form
     */
    public function create()
    {
        return view('dashboard.admin.staff.create');
    }

    /**
     * Store new staff member
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
            'name.required' => 'Staff name is required',
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
            'email_verified_at' => now(), // Staff don't need email verification
        ]);

        $user->assignRole('staff');

        return redirect()->route('staff.index')
            ->with('success', "Staff '{$user->name}' created successfully!");
    }

    /**
     * Show edit form
     */
    public function edit(User $staff)
    {
        $this->authorize('update', $staff);
        return view('dashboard.admin.staff.edit', compact('staff'));
    }

    /**
     * Update staff
     */
    public function update(Request $request, User $staff)
    {
        $this->authorize('update', $staff);

        $data = $request->validate([
            'name' => 'required|string|min:3|max:191',
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('users', 'email')->ignore($staff->id),
            ],
        ]);

        $staff->update($data);

        return redirect()->route('staff.index')
            ->with('success', "Staff '{$staff->name}' updated successfully!");
    }

    /**
     * Delete staff
     */
    public function destroy(User $staff)
    {
        $this->authorize('delete', $staff);
        
        $name = $staff->name;
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', "Staff '{$name}' deleted successfully!");
    }

    /**
     * Toggle staff status (active/inactive)
     */
    public function toggleStatus(User $staff)
    {
        $this->authorize('update', $staff);
        
        // Using a simple approach: soft delete for inactive
        if ($staff->deleted_at) {
            $staff->restore();
            $status = 'active';
        } else {
            $staff->delete();
            $status = 'inactive';
        }

        return redirect()->route('staff.index')
            ->with('success', "Staff '{$staff->name}' is now {$status}.");
    }
}
