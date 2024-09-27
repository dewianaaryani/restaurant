<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function summary(Order $order)
    {
        return view('users.orders.summary', compact('order'));
    }
    public function index()
    {
        // Retrieve all orders for the authenticated user
        $orders = auth()->user()->orders()->with('orderDetails')->get();

        return view('users.orders.index', compact('orders'));
    }
    public function view($id)
    {
        $order = auth()->user()->orders()->with('orderDetails')->findOrFail($id);

        return view('users.orders.view', compact('order'));
    }
    public function uploadPaymentProof(Request $request, $orderId)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $order = Order::findOrFail($orderId);
            $paymentProof = $request->file('image');

            // Check if the file was uploaded
            if (!$paymentProof->isValid()) {
                return redirect()->back()->with('error', 'Uploaded file is not valid.');
            }

            // Store the image
            $path = $paymentProof->store('payment_proofs', 'public');

            // Update the order with the payment proof path
            $order->image = $path;
            $order->payment_status = 'pending';
            $order->save();

            return redirect()->back()->with('success', 'Payment proof uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload payment proof: ' . $e->getMessage());
        }
    }





}
