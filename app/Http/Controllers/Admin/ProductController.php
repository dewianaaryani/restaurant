<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function create()
    {
        return view('admin.products.form');
    }
    public function store(Request $request)
    {
        $this->validateProduct($request);

        // Create a new product
        $product = new Product($request->only(['name', 'category', 'price']));

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);
            $product->image = "img/" . $filename;
        }

        // Save the product
        $product->save();

        // Create a new stock record for the product with a quantity of 0
        $product->stock()->create([
            'quantity' => 0, // Default stock quantity is 0
        ]);

        return redirect()->route('admin.stocks.index')->with('success', 'Product created successfully.');
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.form', compact('product'));
    }

    // Update the product
    public function update(Request $request, $id)
    {
        // Validate the product details
        $this->validateProduct($request);

        // Find the existing product by ID
        $product = Product::findOrFail($id);

        // Update the product's basic details
        $product->fill($request->only(['name', 'category', 'price']));

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            // Upload the new image
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $filename);
            $product->image = "img/" . $filename;
        }

        // Save the updated product
        $product->save();


        return redirect()->route('admin.stocks.index')->with('success', 'Product updated successfully.');
    }


    // Delete a product
    public function destroy($id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Delete the associated stock record if it exists
        if ($product->stock) {
            $product->stock->delete();
        }

        // Delete the product image if it exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Delete the product
        $product->delete();

        return redirect()->route('admin.stocks.index')->with('success', 'Product deleted successfully.');
    }


    // Common validation method for products
    private function validateProduct($request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    }

}
