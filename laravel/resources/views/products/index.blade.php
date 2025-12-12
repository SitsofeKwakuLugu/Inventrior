@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold" style="color: var(--primary-navy);">Products</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-silver">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($products->count() > 0)
        <div class="table-responsive card">
            <table class="table table-hover mb-0">
                <thead style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                    <tr>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ \Illuminate\Support\Str::limit($product->description, 50) ?? 'N/A' }}</td>
                        <td>{{ $product->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Delete this product?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <strong>No products yet.</strong> <a href="{{ route('products.create') }}">Create your first product</a>
        </div>
    @endif
</div>
@endsection