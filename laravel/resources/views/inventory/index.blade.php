<div class="container">
    <h1>Inventory Overview</h1>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Total Stock</th>
                <th>Updated At</th>
            </tr>
        </thead>

        <tbody>
            @forelse($inventory as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->current_stock }}</td>
                <td>{{ $item->updated_at->format('Y-m-d H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No inventory data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection