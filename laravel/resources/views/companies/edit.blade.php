@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="mb-4" style="color: var(--primary-navy);">Edit Company: {{ $company->name }}</h3>

                    <form method="POST" action="{{ route('companies.update', $company) }}">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Company Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   required minlength="3" placeholder="Enter company name"
                                   value="{{ old('name', $company->name) }}">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   required placeholder="company@example.com"
                                   value="{{ old('email', $company->email) }}">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                   maxlength="255" placeholder="Street address"
                                   value="{{ old('address', $company->address) }}">
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   maxlength="20" placeholder="+1 (555) 123-4567"
                                   value="{{ old('phone', $company->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Verification Status:</strong> {{ $company->verified ? 'Verified' : 'Pending Verification' }}
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-silver w-100">Update Company</button>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('companies.destroy', $company) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" 
                                            onclick="return confirm('Are you sure? This will permanently delete the company if it has no active users.')">
                                        Delete Company
                                    </button>
                                </form>
                            </div>
                        </div>

                        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-light w-100 mt-2">Back to Dashboard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#0ea5a5'
    });
@endif

@if($errors->any())
    @foreach($errors->all() as $error)
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $error }}',
            confirmButtonColor: '#0ea5a5'
        });
    @endforeach
@endif
</script>
@endsection
