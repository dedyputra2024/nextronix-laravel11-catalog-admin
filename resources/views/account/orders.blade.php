@extends('layouts.app', ['title' => 'Pesanan Saya'])

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Pesanan Saya</h1>
    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>No Order</th><th>Total</th><th>Payment</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>@rupiah($order->total)</td>
                        <td><span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst(str_replace('_',' ',$order->payment_status)) }}</span><br><small>{{ strtoupper($order->payment_method) }}</small></td>
                        <td><span class="badge bg-primary">{{ ucfirst($order->status) }}</span></td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end"><a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
