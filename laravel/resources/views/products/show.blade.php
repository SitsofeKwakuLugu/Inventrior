<div class="container">
    <h1>Product Details</h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Back</a>

    <div class="card">
        <div class="card-body">
            <h3>{{ $product->name }}</h3>
            <p><strong>SKU:</strong> {{ $product->sku }}</p>
            <p><strong>Price:</strong> ₵{{ number_format($product->price, 2) }}</p>
            <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
            <p><strong>Description:</strong></p>
            <p>{{ $product->description }}</p>
        </div>
    </div>
</div>
@endsection
