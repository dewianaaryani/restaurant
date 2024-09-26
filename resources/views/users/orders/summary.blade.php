@extends('users.layouts.app')

@section('title', 'Order Summary')

@section('content')
    <div class="container mt-5">
        <h2>Order Summary</h2>

        <p>Order ID: {{ $order->id }}</p>
        <p>Total Price: Rp. {{ number_format($order->total_price, 0, ',', '.') }}</p>
        <p>Status: {{ $order->status }}</p>
        <p>Payment Status: {{ $order->payment_status }}</p>

        <h3>Order Details</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
    </div>
@endsection
