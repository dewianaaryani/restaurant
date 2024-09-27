<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product')->get();
        return view('admin.stocks.index', compact('stocks'));
    }
    public function addStock(Request $request, Stock $stock)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        // Check the values before updating
        // dd($stock->quantity, $request->qty, $stock->quantity + $request->qty);

        $stock->update([
            'quantity' => $stock->quantity + $request->qty,
        ]);

        return redirect()->back()->with('success', 'Stock added successfully.');
    }
}
