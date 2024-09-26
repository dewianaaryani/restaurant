<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function summary(Order $order)
    {
        return view('users.orders.summary', compact('order'));
    }
}
