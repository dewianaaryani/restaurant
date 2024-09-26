<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    public function add(Request $request, $productId)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($productId);

        // Check if the product exists and is in stock
        if (!$product || $product->stock->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Product not available in sufficient quantity.');
        }

        // Check if the user is authenticated
        if (Auth::check()) {
            // Check if the product is already in the cart
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // If product already exists in cart, update the quantity
                $cartItem->quantity += $request->quantity;
                $cartItem->save();
            } else {
                // Otherwise, create a new cart item
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $request->quantity,
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart successfully!');
        } else {
            return redirect()->route('login')->with('error', 'Please login to add items to your cart.');
        }
    }
    public function showCart()
    {
        // Fetch the authenticated user's cart items
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        
        return view('users.carts.index', compact('carts'));
    }
    // CartController.php

    public function remove($id)
    {
        $cart = Cart::findOrFail($id);
        // Check if the authenticated user is the owner of the cart item
        if ($cart->user_id == Auth::id()) {
            $cart->delete();
        }

        return redirect()->route('cart.show')->with('success', 'Item removed from cart.');
    }

}
