@extends('users.layouts.app')

@section('title', 'Sales Report')

@section('content')
    <div class="container">
        <form method="GET" action="{{ route('admin.reports.sales') }}">
            <div class="form-group">
                <label for="period">Select Period</label>
                <select name="period" id="period" class="form-control">
                    <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Filter</button>
        </form>
        <h1 class="mt-3">{{ ucfirst($period) }} Sales Report</h1>
        <p><strong>Period:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Sales (Quantity)</th>
                    <th>Total Revenue</th>
                    {{-- <th>Total Profit Margin</th> --}}
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $totalSales }}</td>
                    <td>Rp{{ number_format($totalRevenue, 2) }}</td>
                    {{-- <td>Rp{{ number_format($profitMargin, 2) }}</td> --}}
                </tr>
            </tbody>
        </table>
    </div>
    
    
@endsection
