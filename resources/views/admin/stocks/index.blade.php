@extends('users.layouts.app')
@section('title', 'Products')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Products</h1>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            <tr>
                                <td>{{ $stock->id }}</td>
                                <td>{{ $stock->product->name }}</td>
                                <td>{{ $stock->quantity }}</td>
                                <td>
                                    <!-- Button to trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $stock->id }}">
                                        Add Stock
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="addStockModal{{ $stock->id }}" tabindex="-1" aria-labelledby="addStockModalLabel{{ $stock->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addStockModalLabel{{ $stock->id }}">Add Stock for {{ $stock->product->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.addStock', $stock->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="qty" class="form-label">Quantity to Add</label>
                                                            <input type="number" class="form-control" id="qty" name="qty" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
