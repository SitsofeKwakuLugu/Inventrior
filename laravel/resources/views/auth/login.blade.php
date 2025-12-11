<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-4">Login</h4>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <label class="form-check-label">Remember me</label>
                </div>
                <button class="btn btn-silver w-100">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection