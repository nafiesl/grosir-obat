<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $editableProduct = null;
        $q = $request->get('q');
        $products = Product::where(function ($query) use ($q) {
            if ($q) {
                $query->where('name', 'like', '%'.$q.'%');
            }
        })
            ->orderBy('name')
            ->with('unit')
            ->paginate(25);

        if (in_array($request->get('action'), ['edit', 'delete']) && $request->has('id')) {
            $editableProduct = Product::find($request->get('id'));
        }

        return view('products.index', compact('products', 'editableProduct'));
    }

    public function store(Request $request)
    {
        $newProduct = $request->validate([
            'name'         => 'required|max:20',
            'cash_price'   => 'required|numeric',
            'credit_price' => 'nullable|numeric',
            'unit_id'      => 'required|numeric|exists:product_units,id',
        ]);

        Product::create($newProduct);

        flash(trans('product.created'), 'success');

        return redirect()->route('products.index');
    }

    public function update(Request $request, Product $product)
    {
        $productData = $request->validate([
            'name'         => 'required|max:20',
            'cash_price'   => 'required|numeric',
            'credit_price' => 'nullable|numeric',
            'unit_id'      => 'required|numeric|exists:product_units,id',
        ]);

        $routeParam = $request->only('page', 'q');

        $product->update($productData);

        flash(trans('product.updated'), 'success');

        return redirect()->route('products.index', $routeParam);
    }

    public function destroy(Request $request, Product $product)
    {
        $requestData = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $routeParam = $request->only('page', 'q');

        if ($requestData['product_id'] == $product->id && $product->delete()) {
            flash(trans('product.deleted'), 'success');

            return redirect()->route('products.index', $routeParam);
        }

        flash(trans('product.undeleted'), 'error');

        return back();
    }

    public function priceList()
    {
        $products = Product::orderBy('name')->with('unit')->get();

        return view('products.price-list', compact('products'));

        // $pdf = \PDF::loadView('products.price-list', compact('products'));
        // return $pdf->stream('price-list.pdf');
    }
}
