<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\OrderDetail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        return view('home');
    } 
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome(): View
    {
        // Get most selected products by category
        $mostSelected = [
            'appetizer' => OrderDetail::with('product')
                ->whereHas('product', function ($query) {
                    $query->where('category', 'appetizer');
                })
                ->selectRaw('product_id, SUM(quantity) as total_quantity')
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->take(5) // Get top 5 selected
                ->get(),

            'main_course' => OrderDetail::with('product')
                ->whereHas('product', function ($query) {
                    $query->where('category', 'main_course');
                })
                ->selectRaw('product_id, SUM(quantity) as total_quantity')
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->take(5) // Get top 5 selected
                ->get(),

            'drink' => OrderDetail::with('product')
                ->whereHas('product', function ($query) {
                    $query->where('category', 'drink');
                })
                ->selectRaw('product_id, SUM(quantity) as total_quantity')
                ->groupBy('product_id')
                ->orderBy('total_quantity', 'desc')
                ->take(5) // Get top 5 selected
                ->get(),
        ];

        return view('adminHome', compact('mostSelected'));
    }
    
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
}
