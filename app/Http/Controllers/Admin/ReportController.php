<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $period = $request->input('period', 'weekly'); // default to weekly
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Set date range
        if ($period === 'weekly') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($period === 'monthly') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        // Retrieve all orders within the date range
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with('orderDetails.product') // Load order details with product info
            ->get();

        $totalSales = 0;
        $totalRevenue = 0;
        $totalCost = 0;

        foreach ($orders as $order) {
            foreach ($order->orderDetails as $detail) {
                $totalSales += $detail->quantity;
                $totalRevenue += $detail->price * $detail->quantity;

                // Assuming cost_price is available in the products table
                $totalCost += $detail->product->cost_price * $detail->quantity;
            }
        }

        $profitMargin = $totalRevenue - $totalCost;

        // Return report data
        return view('admin.reports.sales', compact('totalSales', 'totalRevenue', 'profitMargin', 'period', 'startDate', 'endDate'));
    }
}
