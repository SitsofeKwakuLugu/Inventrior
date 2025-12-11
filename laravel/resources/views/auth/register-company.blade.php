@extends('layout.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-4">Register Your Company</h4>

            <form method="POST" action="{{ route('register.company') }}" id="companyForm">
                @csrf
                <div class="mb-3">
                    <label>Company Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           required minlength="3" maxlength="191" placeholder="Enter company name"
                           value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           required maxlength="191" placeholder="company@example.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                           maxlength="255" placeholder="Street address"
                           value="{{ old('address') }}">
                    @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           placeholder="+1 (555) 123-4567"
                           value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-silver w-100 mt-3">Register Company</button>
                <p class="text-center text-muted mt-3">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('companyForm').addEventListener('submit', function(e) {
    // Client-side validation before submission
    const name = document.querySelector('input[name="name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();
    
    if (name.length < 3) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Company name must be at least 3 characters long',
            confirmButtonColor: '#0ea5a5'
        });
        return false;
    }

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

    if (phone && !/^[0-9\-\+\(\)\s]{7,20}$/.test(phone)) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Phone',
            text: 'Please enter a valid phone number',
            confirmButtonColor: '#0ea5a5'
        });
        return false;
    }
});

// Show success alert if redirected back
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