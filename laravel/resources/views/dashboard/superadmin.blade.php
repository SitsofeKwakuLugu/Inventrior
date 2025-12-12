@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-5 fw-bold" style="color: var(--primary-navy);">System Administration</h1>
            <p class="lead text-muted">Overview of all companies and system monitoring</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card p-3" style="border-left: 4px solid var(--primary-teal);">
                <p class="text-muted mb-1">Total Companies</p>
                <h3 style="color: var(--primary-teal); font-weight: 700;">
                    {{ $companies->count() ?? 0 }}
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3" style="border-left: 4px solid #28a745;">
                <p class="text-muted mb-1">Verified Companies</p>
                <h3 style="color: #28a745; font-weight: 700;">
                    {{ $companies->where('verified', true)->count() ?? 0 }}
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3" style="border-left: 4px solid #ffc107;">
                <p class="text-muted mb-1">Pending Verification</p>
                <h3 style="color: #ffc107; font-weight: 700;">
                    {{ $companies->where('verified', false)->count() ?? 0 }}
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3" style="border-left: 4px solid #6c757d;">
                <p class="text-muted mb-1">Total Users</p>
                <h3 style="color: #6c757d; font-weight: 700;">
                    {{ $totalUsers ?? 0 }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid var(--primary-teal);">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="companies-tab" data-bs-toggle="tab" data-bs-target="#companies" 
                    type="button" role="tab" style="color: var(--primary-teal); font-weight: 600;">
                🏢 Companies
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" 
                    type="button" role="tab" style="color: var(--primary-navy); font-weight: 600;">
                All Users
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Companies Tab -->
        <div class="tab-pane fade show active" id="companies" role="tabpanel">
            @if($companies->count() > 0)
                <div class="table-responsive card">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                            <tr>
                                <th>Company Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Users</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td><strong>{{ $company->name }}</strong></td>
                                    <td>{{ $company->email }}</td>
                                    <td>{{ $company->phone ?? 'N/A' }}</td>
                                    <td>
                                            <span class="badge" style="background-color: {{ $company->verified ? '#0ea5a5' : '#ffc107' }}; padding: 6px 12px; color: {{ $company->verified ? 'white' : '#333' }};">
                                                {{ $company->verified ? 'Verified' : 'Pending' }}
                                            </span>
                                    </td>
                                    <td>{{ $company->users->count() }}</td>
                                    <td>{{ $company->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                                    onclick="return confirm('Are you sure? This will delete the company if it has no active users.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card p-5 text-center">
                    <i class="fas fa-building" style="font-size: 48px; color: var(--primary-teal); margin-bottom: 15px;"></i>
                    <h5>No Companies</h5>
                    <p class="text-muted">No companies have registered yet.</p>
                </div>
            @endif
        </div>

        <!-- Users Tab -->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="row mb-3">
                <div class="col-md-12">
                    <input type="text" id="userSearch" class="form-control" placeholder="Search users by name or email..." 
                           style="border-color: var(--primary-teal);">
                </div>
            </div>

            @if($users->count() > 0)
                <div class="table-responsive card">
                    <table class="table table-hover mb-0" id="usersTable">
                        <thead style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="user-row" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $user->role === 'super-admin' ? '#dc3545' : ($user->role === 'company-admin' ? '#0ea5a5' : '#6c757d') }}; padding: 4px 8px;">
                                            {{ ucfirst(str_replace('-', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->company?->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $user->deleted_at ? '#dc3545' : '#28a745' }}; padding: 4px 8px; color: white;">
                                            {{ $user->deleted_at ? 'Inactive' : 'Active' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card p-5 text-center">
                    <i class="fas fa-users" style="font-size: 48px; color: var(--primary-teal); margin-bottom: 15px;"></i>
                    <h5>No Users</h5>
                    <p class="text-muted">No users have registered yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// User search functionality
document.getElementById('userSearch').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    
    rows.forEach(row => {
        const name = row.dataset.name.toLowerCase();
        const email = row.dataset.email.toLowerCase();
        
        if (name.includes(search) || email.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

@endsection
