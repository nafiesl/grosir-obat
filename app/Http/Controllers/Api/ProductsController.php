<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        $queriedProducts = [];
        if ($query) {
            $queriedProducts = Product::where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%');
            })->with('unit')->get();
        }

        return response()->json($queriedProducts, 200);
        // return view('cart.partials.product-search-result-box', $queriedProducts);
    }
}
