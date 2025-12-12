@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                    <h5 class="mb-0" style="color: var(--primary-navy);">Edit Product</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>

                    @include('products.form', [
                        'route' => route('products.update', $product->id),
                        'method' => 'PUT',
                        'product' => $product
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@endsection