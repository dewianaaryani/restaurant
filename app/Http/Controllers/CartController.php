<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

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

    // public function checkout(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Get the user's cart items and ensure it returns a collection, not null
    //         $carts = auth()->user()->carts ?? collect();

    //         // Check if the cart is empty
    //         if ($carts->isEmpty()) {
    //             return redirect()->back()->with('error', 'Your cart is empty.');
    //         }

    //         // Calculate the total price
    //         $totalPrice = $carts->sum(function ($cart) {
    //             return $cart->quantity * $cart->product->price;
    //         });

    //         // Create the order
    //         $order = Order::create([
    //             'user_id' => auth()->id(),
    //             'total_price' => $totalPrice,
    //             'status' => 'pending',
    //             'payment_status' => 'unpaid',
    //         ]);

    //         // Create the order details
    //         foreach ($carts as $cart) {
    //             OrderDetail::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $cart->product_id,
    //                 'quantity' => $cart->quantity,
    //                 'price' => $cart->product->price,
    //             ]);
    //         }

    //         // Clear the cart after checkout
    //         auth()->user()->carts()->delete();

    //         // Commit the transaction
    //         DB::commit();

    //         // Redirect to the order summary page with success message
    //         return redirect()->route('order.summary', $order->id)->with('success', 'Order placed successfully.');
    //     } catch (\Exception $e) {
    //         // Rollback the transaction if something goes wrong
    //         DB::rollBack();

    //         // Log the error for debugging
    //         Log::error('Checkout Error: ' . $e->getMessage());

    //         // Redirect back with an error message
    //         return redirect()->back()->with('error', 'There was an error processing your order. Please try again.');
    //     }
    // }

    public function checkout(Request $request)
    {
        DB::beginTransaction();

        try {
            // Get the user's cart items and ensure it returns a collection, not null
            $carts = auth()->user()->carts ?? collect();

            // Check if the cart is empty
            if ($carts->isEmpty()) {
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            // Calculate the total price
            $totalPrice = $carts->sum(function ($cart) {
                return $cart->quantity * $cart->product->price;
            });

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Create the order details
            foreach ($carts as $cart) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
            }

            // Clear the cart after checkout
            auth()->user()->carts()->delete();

            // Commit the transaction
            DB::commit();

            // Redirect to the order summary page with success message
            return redirect()->route('order.summary', $order->id)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            // Log the error for debugging
            Log::error('Checkout Error: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = auth()->user()->carts()->findOrFail($id);
        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json(['success' => 'Quantity updated successfully']);
    }

}
