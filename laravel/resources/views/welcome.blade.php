@extends('layout.app')

@section('content')
<!-- HERO SECTION -->
<section class="hero-section">

    <div class="container-fluid">

        <div class="row align-items-center h-100">

            <!-- LEFT TEXT -->
            <div class="col-md-8 ps-5 pe-5">

                <h1 class="display-3 fw-bold text-white mb-4">
                    Secure Inventory Control for Modern Companies
                </h1>

                <p class="lead text-light mb-4">
                    Inventrior helps organizations record, monitor, and value their inventory with enterprise-grade security and clarity.
                </p>

                <a href="{{ route('register.company') }}" class="btn btn-silver btn-lg px-5 py-3">
                    Get Started
                </a>

            </div>

        </div>

    </div>

</section>



<!-- INFO SECTION -->
<div class="container my-5">

    <div class="row mb-5">
        <div class="col-md-12">
            <h2 class="text-center mb-5" style="color: var(--primary-teal); font-weight: 700; font-size: 2.5rem;">
                Why Choose Inventrior?
            </h2>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm h-100">
                <h5>Monitor Stock</h5>
                <p class="small">Track quantities in real time with clean, organized records and instant visibility into inventory levels.</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm h-100">
                <h5>Advanced Security</h5>
                <p class="small">Role-based access control, encrypted sessions, protected data, and enterprise-grade security protocols.</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm h-100">
                <h5>Valuation Tools</h5>
                <p class="small">Calculate inventory value using FIFO, LIFO, weighted average, or custom valuation methods.</p>
            </div>
        </div>

    </div>

</div>
@endsection
