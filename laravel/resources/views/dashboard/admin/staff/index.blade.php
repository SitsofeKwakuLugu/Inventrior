@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 style="color: var(--primary-navy);">Staff Management</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('staff.create') }}" class="btn btn-silver">
                <i class="fas fa-plus"></i> Add Staff Member
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($staff->count() > 0)
        <div class="table-responsive card">
            <table class="table table-hover mb-0">
                <thead style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $member)
                        <tr>
                            <td><strong>{{ $member->name }}</strong></td>
                            <td>{{ $member->email }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $member->deleted_at ? '#dc3545' : '#0ea5a5' }}; padding: 6px 12px;">
                                    {{ $member->deleted_at ? 'Inactive' : 'Active' }}
                                </span>
                            </td>
                            <td>{{ $member->created_at->format('M d, Y') }}</td>
                            <td>
                                <form action="{{ route('staff.toggle-status', $member) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Toggle Status">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                </form>
                                <a href="{{ route('staff.edit', $member) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.destroy', $member) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this staff member?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $staff->links() }}
        </div>
    @else
        <div class="card p-5 text-center">
            <i class="fas fa-users" style="font-size: 48px; color: var(--primary-teal); margin-bottom: 15px;"></i>
            <h5>No Staff Members Yet</h5>
            <p class="text-muted">Start adding staff members to your company. They will be able to view inventory and track stock.</p>
            <a href="{{ route('staff.create') }}" class="btn btn-silver mt-3">Add First Staff Member</a>
        </div>
    @endif
</div>
@endsection
