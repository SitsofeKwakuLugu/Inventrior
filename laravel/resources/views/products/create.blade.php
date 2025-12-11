<div class="container">
    <h1>Add Product</h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Back</a>

    @include('products.form', ['route' => route('products.store'), 'method' => 'POST'])
</div>
@endsection