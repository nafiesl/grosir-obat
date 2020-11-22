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
        $draftType = $request->get('draftType');
        $draftKey = $request->get('draftKey');
        $formToken = $request->get('formToken');
        $queriedProducts = [];
        if ($query) {
            $queriedProducts = Product::where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%');
            })->with('unit')->get();
        }

        return view('cart.partials.product-search-result-box', compact('queriedProducts', 'draftType', 'draftKey', 'formToken'));
    }
}
