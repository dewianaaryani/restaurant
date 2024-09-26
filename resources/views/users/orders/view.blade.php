@extends('users.layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Order Details (ID: {{ $order->id }})</h2>

        <p><strong>Total Price:</strong> Rp. {{ number_format($order->total_price, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>

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
        
        <div class="mt-4">
            @if ($order->payment_status === 'unpaid' && !$order->image)
                <p>Kirim Bukti Bayar kamu agar pesanan dapat diproses segera.</p>
                <!-- Button to trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentProofModal">
                    Upload Payment Proof
                </button>
            @elseif ($order->payment_status === 'confirmed')
                <p>Pembayaran kamu telah dikonfirmasi. Pesananmu sedang diproses.</p>
            @elseif ($order->image && $order->payment_status === 'pending')
                <p>Kamu telah mengupload bukti pembayaran. Pembayaranmu sedang dikonfirmasi.</p>
            @else
                <p>Pesananmu telah diproses.</p>
            @endif
        </div>

        <!-- Modal -->
        <div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentProofModalLabel">Upload Payment Proof</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="image" class="form-label">Select Payment Proof Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <p>BCA no rek 00000000 atas nama Udinz</p>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
