<!-- resources/views/products/form.blade.php -->
@extends('users.layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">{{ isset($product) ? 'Edit Product' : 'Create New Product' }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', isset($product) ? $product->name : '') }}" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="appetizer" {{ old('category', isset($product) ? $product->category : '') == 'appetizer' ? 'selected' : '' }}>Appetizer</option>
                    <option value="main_course" {{ old('category', isset($product) ? $product->category : '') == 'main_course' ? 'selected' : '' }}>Main Course</option>
                    <option value="drink" {{ old('category', isset($product) ? $product->category : '') == 'drink' ? 'selected' : '' }}>Drink</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ old('price', isset($product) ? $product->price : '') }}" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image">
                
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update Product' : 'Create Product' }}</button>
            <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
