@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 style="color: var(--primary-navy);">Admin Management</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.create') }}" class="btn btn-silver">
                <i class="fas fa-plus"></i> Add Admin
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($admins->count() > 0)
        <div class="table-responsive card">
            <table class="table table-hover mb-0">
                <thead style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Verification Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td><strong>{{ $admin->name }}</strong></td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $admin->hasVerifiedEmail() ? '#0ea5a5' : '#ffc107' }}; padding: 6px 12px; color: {{ $admin->hasVerifiedEmail() ? 'white' : '#333' }};">
                                    {{ $admin->hasVerifiedEmail() ? 'Verified' : 'Pending' }}
                                </span>
                            </td>
                            <td>{{ $admin->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.edit', $admin) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($admins->count() > 1 && auth()->user()->id !== $admin->id)
                                    <form action="{{ route('admin.destroy', $admin) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                                onclick="return confirm('Are you sure? This admin will be removed.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $admins->links() }}
        </div>
    @else
        <div class="card p-5 text-center">
            <i class="fas fa-user-tie" style="font-size: 48px; color: var(--primary-teal); margin-bottom: 15px;"></i>
            <h5>No Additional Admins</h5>
            <p class="text-muted">Add administrative accounts for your company's top executives.</p>
            <a href="{{ route('admin.create') }}" class="btn btn-silver mt-3">Add First Admin</a>
        </div>
    @endif
</div>
@endsection
