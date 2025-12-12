@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="mb-4" style="color: var(--primary-navy);">Add New Staff Member</h3>

                    <form method="POST" action="{{ route('staff.store') }}" id="staffForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   required minlength="3" placeholder="Enter staff member's full name"
                                   value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   required placeholder="staff@company.com"
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required minlength="8" placeholder="Min 8 characters"
                                   pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).*">
                            <small class="text-muted d-block mt-2">
                                Must include: uppercase, lowercase, number, special character (@$!%*?&)
                            </small>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-silver w-100 mt-4">Create Staff Account</button>
                        <a href="{{ route('staff.index') }}" class="btn btn-light w-100 mt-2">Back to Staff</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('staffForm').addEventListener('submit', function(e) {
    const name = document.querySelector('input[name="name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value;
    
    if (name.length < 3) {
        e.preventDefault();
        Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Name must be at least 3 characters', confirmButtonColor: '#0ea5a5' });
        return false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Please enter a valid email', confirmButtonColor: '#0ea5a5' });
        return false;
    }

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(password)) {
        e.preventDefault();
        Swal.fire({ icon: 'error', title: 'Weak Password', html: 'Must have uppercase, lowercase, number, and special character (@$!%*?&)', confirmButtonColor: '#0ea5a5' });
        return false;
    }
});
</script>
@endsection
