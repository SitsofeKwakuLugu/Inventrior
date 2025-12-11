@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Super Admin Dashboard</h1>
            <p class="lead">Welcome back, {{ auth()->user()->name }}!</p>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Products</h5>
                            <p class="card-text">Manage all products across companies.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">View Products</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Inventory</h5>
                            <p class="card-text">Monitor inventory across all companies.</p>
                            <a href="{{ route('inventory.index') }}" class="btn btn-primary">View Inventory</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Stock Movements</h5>
                            <p class="card-text">Track all stock movement activities.</p>
                            <a href="{{ route('stockmovements.index') }}" class="btn btn-primary">View Movements</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
