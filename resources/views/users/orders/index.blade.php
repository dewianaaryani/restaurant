@extends('users.layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Your Orders</h2>
        
        @if($orders->isEmpty())
            <div class="alert alert-warning" role="alert">
                You have no orders yet.
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Order ID</th>
                        <th class="text-center">Total Price</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Payment Status</th>
                        <th class="text-center">Action</th>
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
                            <a href="{{ route('orders.view', $order->id) }}" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
