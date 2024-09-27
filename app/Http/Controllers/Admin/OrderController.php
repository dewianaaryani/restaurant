<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Display a list of orders
    public function index()
    {
        $orders = Order::with('orderDetails')->orderBy('created_at', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    // Display a specific order
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    // Update order status
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }

    public function approvePayment(Order $order)
    {
        $order->update([
            'status' => 'in-progress',
            'payment_status' => 'confirmed',
        ]);
        // Loop through each order detail to reduce the stock in the 'stocks' table
        foreach ($order->orderDetails as $orderDetail) {
            $product = $orderDetail->product;

            // Find the stock record for the product
            $stock = \App\Models\Stock::where('product_id', $product->id)->first();

            if (!$stock) {
                return redirect()->back()->with('error', 'Stock record not found for product: ' . $product->name);
            }

            if ($stock->quantity < $orderDetail->quantity) {
                return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
            }

            // Reduce the stock by the ordered quantity
            $stock->quantity -= $orderDetail->quantity;
            $stock->save();
        }
        
        
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
    public function OrderCompleted(Order $order)
    {
        $order->update([
            'status' => 'completed',
        ]);
        
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
    // public function processPayment(Request $request, Order $order)
    // {
    //     $validatedData = $request->validate([
    //         'total_payment' => ['required', 'integer', 'min:' . $order->total_price],
    //     ], [
    //         'total_payment.min' => 'Total payment must be greater than or equal to the total price.',
    //         'total_payment.required' => 'Total payment is required.',
    //         'total_payment.integer' => 'Total payment must be a valid number.',
    //     ]);
    
    //     try {
    //         // Update the order with total payment and calculate the change
    //         $order->update([
    //             'status' => 'in-progress',
    //             'payment_status' => 'confirmed',
    //             'total_payment' => $validatedData['total_payment'],
    //             'change' => $validatedData['total_payment'] - $order->total_price,
    //         ]);
    
    //         return redirect()->back()->with('success', 'Payment processed successfully.');
    //     } catch (\Exception $e) {
    //         // Return back with an error message if something goes wrong
    //         return redirect()->back()->with('error', 'There was an issue processing the payment. Please try again.');
    //     }
        
    // }
    

    public function processPayment(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'total_payment' => ['required', 'integer', 'min:' . $order->total_price],
        ], [
            'total_payment.min' => 'Total payment must be greater than or equal to the total price.',
            'total_payment.required' => 'Total payment is required.',
            'total_payment.integer' => 'Total payment must be a valid number.',
        ]);

        DB::beginTransaction();

        try {
            // Update the order with total payment and calculate the change
            $order->update([
                'status' => 'in-progress',
                'payment_status' => 'confirmed',
                'total_payment' => $validatedData['total_payment'],
                'change' => $validatedData['total_payment'] - $order->total_price,
            ]);

            // Loop through each order detail to reduce the stock in the 'stocks' table
            foreach ($order->orderDetails as $orderDetail) {
                $product = $orderDetail->product;

                // Find the stock record for the product
                $stock = \App\Models\Stock::where('product_id', $product->id)->first();

                if (!$stock) {
                    return redirect()->back()->with('error', 'Stock record not found for product: ' . $product->name);
                }

                if ($stock->quantity < $orderDetail->quantity) {
                    return redirect()->back()->with('error', 'Insufficient stock for product: ' . $product->name);
                }

                // Reduce the stock by the ordered quantity
                $stock->quantity -= $orderDetail->quantity;
                $stock->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Payment processed and stock updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'There was an issue processing the payment. Please try again.');
        }
    }

}
