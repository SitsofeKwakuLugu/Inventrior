@extends('layout.app')

@section('content')
<?php use Illuminate\Support\Str; ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold" style="color: var(--primary-navy);">Company Dashboard</h1>
            <p class="lead text-muted">{{ auth()->user()->company->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="card bg-light p-3" style="border-left: 4px solid var(--primary-teal);">
                <p class="text-muted mb-1">Total Inventory Value</p>
                <h3 style="color: var(--primary-teal);">$0.00</h3>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid var(--primary-teal);">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" 
                    type="button" role="tab" style="color: var(--primary-teal); font-weight: 600;">
                Inventory
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" 
                    type="button" role="tab" style="color: var(--primary-navy); font-weight: 600;">
                Staff
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" 
                    type="button" role="tab" style="color: var(--primary-navy); font-weight: 600;">
                Admins
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Inventory Tab -->
        <div class="tab-pane fade show active" id="inventory" role="tabpanel">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 style="color: var(--primary-navy);">Inventory Management</h3>
                        <a href="{{ route('products.create') }}" class="btn btn-silver">
                            <i class="fas fa-plus"></i> Add Product
                        </a>
                    </div>
                </div>
            </div>

            @if($products->count() > 0)
                <!-- Products with Inventory Section -->
                <div class="card mb-4">
                    <div class="card-header" style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                        <h5 class="mb-0" style="color: var(--primary-navy);">Products in Inventory</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f9f9f9;">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hasInventory = false;
                                @endphp
                                @foreach($products as $product)
                                    @if($product->inventory)
                                        @php $hasInventory = true; @endphp
                                        <tr>
                                            <td><strong>{{ $product->name }}</strong></td>
                                            <td>{{ $product->inventory->quantity ?? 0 }} units</td>
                                            <td>${{ number_format($product->inventory->unit_price ?? 0, 2) }}</td>
                                            <td style="color: var(--primary-teal); font-weight: 600;">
                                                ${{ number_format(($product->inventory->quantity ?? 0) * ($product->inventory->unit_price ?? 0), 2) }}
                                            </td>
                                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $product->id }}">
                                                    <i class="fas fa-box"></i> Add Stock
                                                </button>
                                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Delete this product?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!$hasInventory)
                        <div class="card-body">
                            <p class="text-muted mb-0">No products in inventory yet. Add stock to your products using the "Add Inventory" button below.</p>
                        </div>
                    @endif
                </div>

                <!-- Products without Inventory Section -->
                <div class="card">
                    <div class="card-header" style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                        <h5 class="mb-0" style="color: var(--primary-navy);">Add Inventory to Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $productsWithoutInventory = $products->filter(fn($p) => !$p->inventory)->values();
                            @endphp
                            @forelse($productsWithoutInventory as $product)
                                <div class="col-md-6 mb-3">
                                    <div class="card" style="border-left: 4px solid var(--primary-teal);">
                                        <div class="card-body">
                                            <h6 style="color: var(--primary-navy);">{{ $product->name }}</h6>
                                            <p class="text-muted small mb-3">{{ Str::limit($product->description, 60) ?? 'No description' }}</p>
                                            <button type="button" class="btn btn-sm btn-silver" data-bs-toggle="modal" data-bs-target="#addInventoryModal{{ $product->id }}">
                                                <i class="fas fa-plus"></i> Add Inventory
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12">
                                    <p class="text-muted">All products have inventory. Create a new product to add more inventory.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <strong>No products yet.</strong> <a href="{{ route('products.create') }}">Add your first product</a>
                </div>
            @endif
        </div>

        <!-- Staff Tab -->
        <div class="tab-pane fade" id="staff" role="tabpanel">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 style="color: var(--primary-navy);">Staff Management</h3>
                        <a href="{{ route('staff.create') }}" class="btn btn-silver">
                            <i class="fas fa-plus"></i> Add Staff
                        </a>
                    </div>
                </div>
            </div>

            @if($staff->count() > 0)
                <div class="table-responsive card">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $member)
                                <tr>
                                    <td><strong>{{ $member->name }}</strong></td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $member->deleted_at ? '#dc3545' : '#0ea5a5' }}; padding: 6px 12px; color: white;">
                                            {{ $member->deleted_at ? 'Inactive' : 'Active' }}
                                        </span>
                                    </td>
                                    <td>{{ $member->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <form action="{{ route('staff.toggle-status', $member) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('staff.edit', $member) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('staff.destroy', $member) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Delete this staff member?')">
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
                    {{ $staff->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <strong>No staff members yet.</strong> <a href="{{ route('staff.create') }}">Add your first staff member</a>
                </div>
            @endif
        </div>

        <!-- Admins Tab -->
        <div class="tab-pane fade" id="admins" role="tabpanel">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 style="color: var(--primary-navy);">Admin Management</h3>
                        <a href="{{ route('admin.create') }}" class="btn btn-silver">
                            <i class="fas fa-plus"></i> Add Admin
                        </a>
                    </div>
                </div>
            </div>

            @if($admins->count() > 0)
                <div class="table-responsive card">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verification Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td><strong>{{ $admin->name }}</strong></td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $admin->hasVerifiedEmail() ? '#0ea5a5' : '#ffc107' }}; padding: 6px 12px; color: {{ $admin->hasVerifiedEmail() ? 'white' : '#333' }};">
                                            {{ $admin->hasVerifiedEmail() ? 'Verified' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $admin->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.edit', $admin) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($admins->count() > 1 && auth()->user()->id !== $admin->id)
                                            <form action="{{ route('admin.destroy', $admin) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Delete this admin?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $admins->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <strong>No additional admins.</strong> <a href="{{ route('admin.create') }}">Add admin</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Inventory Modals -->
    @foreach($products->filter(fn($p) => !$p->inventory) as $product)
    <div class="modal fade" id="addInventoryModal{{ $product->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                    <h5 class="modal-title">Add Inventory - {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('inventory.add', ['inventory' => $product->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required min="1" placeholder="Enter quantity">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Price ($)</label>
                            <input type="number" name="unit_cost" class="form-control" required min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-silver">Add to Inventory</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Add Stock Modals -->
@foreach($products->filter(fn($p) => $p->inventory) as $product)
    <div class="modal fade" id="addStockModal{{ $product->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--light-silver); border-bottom: 2px solid var(--primary-teal);">
                    <h5 class="modal-title">Add Stock - {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('inventory.add', ['inventory' => $product->inventory->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Quantity to Add</label>
                            <input type="number" name="quantity" class="form-control" required min="1" placeholder="Enter quantity">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Cost ($)</label>
                            <input type="number" name="unit_cost" class="form-control" required min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="alert alert-info small">
                            <strong>Current Stock:</strong> {{ $product->inventory->quantity }} units @ ${{ number_format($product->inventory->unit_price, 2) }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-silver">Add Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

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
</script>
@endsection
