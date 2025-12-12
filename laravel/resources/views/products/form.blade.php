<form action="{{ $route }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($method)) @method($method) @endif

    <div class="mb-3">
        <label class="form-label">Product Name *</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $product->name ?? '') }}" required minlength="3" maxlength="191">
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Product description">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        <small class="text-muted d-block mt-2">Supported formats: JPG, PNG, GIF (Max 2MB)</small>
        @if($product && $product->image_path)
            <div class="mt-2">
                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="max-width: 150px; height: auto;">
            </div>
        @endif
        @error('image')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-silver">
            <i class="fas fa-save"></i> {{ isset($product->id) ? 'Update Product' : 'Create Product' }}
        </button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>