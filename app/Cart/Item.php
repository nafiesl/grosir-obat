<?php

namespace App\Cart;

use App\Product;

/**
 * Draft Item class.
 */
class Item
{
    public $id;
    public $product;
    public $name;
    public $price;
    public $qty;
    public $item_discount = 0;
    public $item_discount_subtotal = 0;
    public $subtotal;

    public function __construct(Product $product, $qty)
    {
        $this->id = $product->id;
        $this->product = $product;
        $this->qty = $qty;
        $this->price = $product->getPrice();
        $this->subtotal = $product->getPrice() * $qty;
    }

    public function updateAttribute(array $newItemData)
    {
        if (isset($newItemData['qty'])) {
            $this->qty = $newItemData['qty'];
            $this->subtotal = $this->price * $this->qty;
        }

        if (isset($newItemData['item_discount'])) {
            $this->item_discount = $newItemData['item_discount'];
            $this->item_discount_subtotal = $this->item_discount * $this->qty;
            $this->subtotal = $this->subtotal - $this->item_discount_subtotal;
        }

        return $this;
    }
}
