@extends('users.layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Manage Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th class="text-center">Order ID</th>
                <th class="text-center">Total Price</th>
                <th class="text-center">Status</th>
                <th class="text-center">Payment Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td class="text-center">{{ $order->id }}</td>
                    <td class="text-center">Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ ucfirst($order->status) }}</td>
                    <td class="text-center">{{ ucfirst($order->payment_status) }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">View</a>
                        <!-- Add more action buttons as needed -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
