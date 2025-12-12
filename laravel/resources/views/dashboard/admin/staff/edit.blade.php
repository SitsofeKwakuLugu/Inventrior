@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="mb-4" style="color: var(--primary-navy);">Edit Staff Member</h3>

                    <form method="POST" action="{{ route('staff.update', $staff) }}">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   required minlength="3" placeholder="Enter staff member's full name"
                                   value="{{ old('name', $staff->name) }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   required placeholder="staff@company.com"
                                   value="{{ old('email', $staff->email) }}">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Password cannot be changed from this form.
                        </div>

                        <button type="submit" class="btn btn-silver w-100 mt-4">Update Staff</button>
                        <a href="{{ route('staff.index') }}" class="btn btn-light w-100 mt-2">Back to Staff</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
