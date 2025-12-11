@extends('layout.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-4">Register Company Administrator</h4>
            <p class="text-muted mb-4">Company: <strong>{{ $company->name }}</strong></p>

            <form method="POST" action="{{ route('register.admin', $company) }}" id="adminForm">
                @csrf
                <div class="mb-3">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           required minlength="3" maxlength="191" placeholder="Full name"
                           value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           required maxlength="191" placeholder="admin@company.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Password *</label>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror" 
                           required minlength="8" placeholder="Min 8 characters"
                           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).*">
                    <small class="text-muted d-block mt-2">
                        Password must contain: uppercase, lowercase, number, special character (@$!%*?&)
                    </small>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Confirm Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control @error('password') is-invalid @enderror" 
                           required minlength="8" placeholder="Confirm password">
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-silver w-100 mt-3">Register Administrator</button>
                <p class="text-center text-muted mt-3">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('adminForm').addEventListener('submit', function(e) {
    const name = document.querySelector('input[name="name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value;
    const passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;
    
    // Name validation
    if (name.length < 3) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Full name must be at least 3 characters',
            confirmButtonColor: '#0ea5a5'
        });
        return false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address',
            confirmButtonColor: '#0ea5a5'
        });
        return false;
    }

    // Password validation
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(password)) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Weak Password',
            html: 'Password must be at least 8 characters and include:<br>• Uppercase letter<br>• Lowercase letter<br>• Number<br>• Special character (@$!%*?&)',
            confirmButtonColor: '#0ea5a5'
        });
        return false;
    }

    // Password match
    if (password !== passwordConfirm) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Passwords Don\'t Match',
            text: 'Please ensure both password fields match',
            confirmButtonColor: '#0ea5a5'
        });
        return false;
    }
});

// Show success alert if redirected
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        html: '{{ session('success') }}',
        confirmButtonColor: '#0ea5a5'
    });
@endif
</script>
@endsection