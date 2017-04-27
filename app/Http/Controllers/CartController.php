<?php

namespace App\Http\Controllers;

use App\Cart\CartCollection;
use App\Cart\CashDraft;
use App\Cart\CreditDraft;
use App\Cart\Item;
use App\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $cart;

    public function __construct()
    {
        $this->cart = new CartCollection();
    }

    public function index(Request $request)
    {
        $queriedProducts = [];
        $draft = $this->cart->content()->first();

        return view('cart.index', compact('draft', 'queriedProducts'));
    }

    public function show(Request $request, $draftKey)
    {
        $query = $request->get('query');
        $queriedProducts = [];
        if ($query) {
            $queriedProducts = Product::where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%');
            })->get();
        }

        $draft = $this->cart->get($draftKey);

        return view('cart.index', compact('draft', 'queriedProducts'));
    }

    public function add(Request $request)
    {
        if ($request->has('create-cash-draft')) {
            $this->cart->add(new CashDraft());
        } else {
            $this->cart->add(new CreditDraft());
        }

        return redirect()->route('cart.show', $this->cart->content()->last()->draftKey);
    }

    public function addDraftItem(Request $request, $draftKey, Product $product)
    {
        $item = new Item($product, $request->qty);
        $this->cart->addItemToDraft($draftKey, $item);

        return back();
    }

    public function updateDraftItem(Request $request, $draftKey)
    {
        $this->cart->updateDraftItem($draftKey, $request->item_key, $request->only('qty', 'item_discount'));

        return back();
    }

    public function removeDraftItem(Request $request, $draftKey)
    {
        $this->cart->removeItemFromDraft($draftKey, $request->item_index);

        return back();
    }

    public function empty($draftKey)
    {
        $this->cart->emptyDraft($draftKey);

        return redirect()->route('cart.index', $draftKey);
    }

    public function remove(Request $request)
    {
        $this->cart->removeDraft($request->draft_key);

        if ($this->cart->isEmpty())
            return redirect()->route('cart.index');

        $lastDraft = $this->cart->content()->last();
        return redirect()->route('cart.show', $lastDraft->draftKey);
    }

    public function destroy()
    {
        $this->cart->destroy();

        return redirect()->route('cart.index');
    }

    public function proccess(Request $request, $draftKey)
    {
        $this->cart->updateDraftAttributes($draftKey, $request->only('customer','notes','payment'));
        return redirect()->route('cart.show', [$draftKey, 'action' => 'confirm']);
    }
}
