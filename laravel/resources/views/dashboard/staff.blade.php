@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-5 fw-bold" style="color: var(--primary-navy);">Staff Dashboard</h1>
            <p class="lead text-muted">{{ auth()->user()->company->name ?? 'Company Inventory' }}</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text" style="background-color: var(--primary-teal); color: white; border: none;">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search products by name..." 
                       style="border-left: none; border-color: var(--primary-teal);">
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-teal" onclick="filterByPrice('asc')" title="Sort by price (Low to High)">
                    <i class="fas fa-arrow-up"></i> Price
                </button>
                <button type="button" class="btn btn-outline-teal" onclick="filterByPrice('desc')" title="Sort by price (High to Low)">
                    <i class="fas fa-arrow-down"></i> Price
                </button>
            </div>
        </div>
    </div>

    <!-- Inventory Grid -->
    <div class="row" id="inventoryContainer">
        <div class="col-12 text-center py-5">
            <p class="text-muted">Loading inventory...</p>
        </div>
    </div>
</div>

<style>
.btn-outline-teal {
    color: var(--primary-teal);
    border-color: var(--primary-teal);
}
.btn-outline-teal:hover {
    background-color: var(--primary-teal);
    color: white;
}
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e0e0e0;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(14, 165, 165, 0.15);
}
.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background-color: var(--light-silver);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fetch inventory data from the server
const products = @json($products);

function renderProducts(items) {
    const container = document.getElementById('inventoryContainer');
    
    if (items.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-search" style="font-size: 48px; color: var(--primary-teal); margin-bottom: 15px;"></i>
                <p class="text-muted mt-3">No products found matching your search.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = items.map(product => `
        <div class="col-md-4 mb-4">
            <div class="card product-card h-100">
                <div style="background-color: var(--light-silver); display: flex; align-items: center; justify-content: center; height: 200px;">
                    ${product.image ? 
                        `<img src="${product.image}" alt="${product.name}" class="product-image">` :
                        `<i class="fas fa-cube" style="font-size: 64px; color: var(--primary-teal);"></i>`
                    }
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary-navy);">${product.name}</h5>
                    <p class="card-text text-muted small">${product.description}</p>
                    
                    <div class="row mt-3 mb-3">
                        <div class="col-6">
                            <small class="text-muted">In Stock</small>
                            <p class="h6" style="color: var(--primary-teal);">${product.quantity} units</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Unit Price</small>
                            <p class="h6" style="color: var(--primary-navy);">$${product.price.toFixed(2)}</p>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <p class="mb-0"><strong style="color: var(--primary-teal); font-size: 1.1em;">$${(product.quantity * product.price).toFixed(2)}</strong></p>
                        <small class="text-muted">Total value</small>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const filtered = products.filter(p => 
        p.name.toLowerCase().includes(search) || 
        p.description.toLowerCase().includes(search)
    );
    renderProducts(filtered);
});

// Price filter
function filterByPrice(order) {
    const sorted = [...products].sort((a, b) => {
        return order === 'asc' ? a.price - b.price : b.price - a.price;
    });
    renderProducts(sorted);
}

// Initial render
renderProducts(products);
</script>
@endsection
