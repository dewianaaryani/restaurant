@extends('users.layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1>Admin Dashboard</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Most Selected Appetizers</h2>
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach ($mostSelected['appetizer'] as $item)
                                            <li class="list-group-item">
                                                {{ $item->product->name }} ({{ $item->total_quantity }} ordered)
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Most Selected Main Courses</h2>
                                    <i class="fas fa-pizza-slice"></i>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach ($mostSelected['main_course'] as $item)
                                            <li class="list-group-item">
                                                {{ $item->product->name }} ({{ $item->total_quantity }} ordered)
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Most Selected Drinks</h2>
                                    <i class="fas fa-coffee"></i>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach ($mostSelected['drink'] as $item)
                                            <li class="list-group-item">
                                                {{ $item->product->name }} ({{ $item->total_quantity }} ordered)
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection