<div class="container">
    <h1>Stock Movements</h1>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Reference</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            @forelse($movements as $move)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $move->product->name }}</td>
                <td>
                    @if($move->type === 'in')
                        <span class="badge bg-success">IN</span>
                    @else
                        <span class="badge bg-danger">OUT</span>
                    @endif
                </td>
                <td>{{ $move->quantity }}</td>
                <td>{{ $move->reference }}</td>
                <td>{{ $move->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No movements recorded</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection*