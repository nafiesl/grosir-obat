<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use PDF;

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
        $this->validate($request, [
            'name'         => 'required|max:20',
            'cash_price'   => 'required|numeric',
            'credit_price' => 'nullable|numeric',
            'unit_id'      => 'required|numeric',
        ]);

        Product::create($request->only('name', 'cash_price', 'credit_price', 'unit_id'));

        flash(trans('product.created'), 'success');

        return redirect()->route('products.index');
    }

    public function update(Request $request, $productId)
    {
        $this->validate($request, [
            'name'         => 'required|max:20',
            'cash_price'   => 'required|numeric',
            'credit_price' => 'nullable|numeric',
        ]);

        $routeParam = $request->only('page', 'q');

        $product = Product::findOrFail($productId)->update($request->only('name', 'cash_price', 'credit_price', 'unit_id'));

        flash(trans('product.updated'), 'success');

        return redirect()->route('products.index', $routeParam);
    }

    public function destroy(Request $request, $productId)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
        ]);

        $routeParam = $request->only('page', 'q');

        if ($request->get('product_id') == $productId && Product::findOrFail($productId)->delete()) {
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

        // $pdf = PDF::loadView('products.price-list', compact('products'));
        // return $pdf->stream('price-list.pdf');
    }
}
