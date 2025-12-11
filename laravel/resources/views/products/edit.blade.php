<div class="container">
    <h1>Edit Product</h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Back</a>

    @include('products.form', [
        'route' => route('products.update', $product->id),
        'method' => 'PUT',
        'product' => $product
    ])
</div>
@endsection