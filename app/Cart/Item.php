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
        $this->id       = $product->id;
        $this->name     = $product->name;
        $this->product  = $product;
        $this->qty      = $qty;
        $this->price    = $product->getPrice();
        $this->subtotal = $product->getPrice() * $qty;
    }

    public function updateAttribute(array $newItemData)
    {
        if (isset($newItemData['qty'])) {
            $this->qty = $newItemData['qty'];
            $this->subtotal = $this->price * $this->qty;
        }

        if (isset($newItemData['item_discount'])) {
            $this->setItemDiscount($newItemData['item_discount']);
        }

        return $this;
    }

    public function setItemDiscount(int $discount)
    {
        $this->item_discount = $discount;
        $this->item_discount_subtotal = $discount * $this->qty;
        $this->subtotal = $this->subtotal - $this->item_discount_subtotal;
    }
}
