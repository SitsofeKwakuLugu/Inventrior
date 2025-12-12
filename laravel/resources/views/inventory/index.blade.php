<div class="container">
    <h1>Inventory Overview</h1>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                        <th>Updated At</th>
                    </tr>
        </thead>

        <tbody>
            @forelse($inventories as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->product->name }}</strong></td>
                <td>{{ $item->quantity }}</td>
                <td>₵{{ number_format($item->unit_price ?? 0, 2) }}</td>
                <td>₵{{ number_format(($item->quantity * ($item->unit_price ?? 0)), 2) }}</td>
                <td>{{ $item->updated_at->format('M d, Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No inventory data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection