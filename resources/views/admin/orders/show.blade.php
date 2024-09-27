@extends('users.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Order Details (ID: {{ $order->id }})</h2>

    <p><strong>Total Price:</strong> Rp. {{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->type) }}</p>
    @if ($order->image)
        <p><strong>Image:</strong> <a href="{{ asset('storage/' . $order->image) }}" class="btn btn-primary" target="_blank" rel="noopener noreferrer">Download Payment Proof</a></p>
    @endif
    @if($order->total_payment && $order->change)
        <p><strong>Total Bayar: </strong> {{ number_format($order->total_payment, 0, ',', '.') }}</p>
        <p><strong>Change: </strong> {{ number_format($order->change, 0, ',', '.') }}</p>
    @endif

    <h4 class="mt-4">Order Items:</h4>
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">Product Name</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $detail)
                <tr>
                    <td class="text-center">{{ $detail->product->name }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-center">Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if ($order->type == "transfer")
        @if ($order->payment_status == 'unpaid')
            <p>The customer has not yet paid for the order.</p>
        @elseif ($order->payment_status == 'pending')
            <form action="{{ route('admin.orders.approvePayment', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Approve Payment</button>
            </form>
        @endif
    @elseif ($order->type == "pay_at_cashier")
        @if ($order->payment_status == 'unpaid')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentProofModal">
                Process Payment
            </button>
            {{-- <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Process Payment</button>
            </form> --}}
        @endif
    @endif
    
    @if ($order->status == 'in-progress')
        <form action="{{ route('admin.orders.orderCompleted', $order->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Order Complete</button>
        </form>
    @endif
</div>
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentProofModalLabel">Payment In Cashier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.orders.processPayment', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="image" class="form-label">Total Bayar</label>
                        <input type="text" class="form-control" id="total_payment" name="total_payment" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
