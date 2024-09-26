<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
class PageController extends Controller
{
    public function landingPage(){
        $products = Product::all();
        return view('users.welcome', compact('products'));
    }
}
