<?php

namespace App\Cart;

use Illuminate\Support\Collection;

/**
* Cart Collection Class
*/
class CartCollection
{
    private $instance;
    private $session;

    public function __construct()
    {
        $this->session = session();
        $this->instance('drafts');
    }

    public function instance($instance = null)
    {
        $instance = $instance ?: 'drafts';

        $this->instance = sprintf('%s.%s', 'transactions', $instance);

        return $this;
    }

    public function currentInstance()
    {
        return str_replace('transactions.', '', $this->instance);
    }

    public function add(TransactionDraft $draft)
    {
        $content = $this->getContent();
        $draft->draftKey = str_random(10);
        $content->put($draft->draftKey, $draft);

        $this->session->put($this->instance, $content);

        return $draft;
    }

    public function get($draftKey)
    {
        $content = $this->getContent();
        if (isset($content[$draftKey]))
            return $content[$draftKey];

        return null;
    }

    public function removeDraft($draftKey)
    {
        $content = $this->getContent();
        $content->pull($draftKey);
        $this->session->put($this->instance, $content);
    }

    public function content()
    {
        if (is_null($this->session->get($this->instance))) {
            return collect([]);
        }

        return $this->session->get($this->instance);
    }

    protected function getContent()
    {
        $content = $this->session->has($this->instance) ? $this->session->get($this->instance) : collect([]);

        return $content;
    }

    public function keys()
    {
        return $this->getContent()->keys();
    }

    public function destroy()
    {
        $this->session->remove($this->instance);
    }

    public function addItemToCart($draftKey, $item)
    {
        $content = $this->getContent();
        $content[$draftKey]->addItem($item);
        $content[$draftKey] = $this->updateCart($content[$draftKey]);

        $this->session->put($this->instance, $content);
    }

    public function updateCart(Cart $draft)
    {
        $draft->charged_weight = $draft->getChargedWeight();
        $draft->items_count = $draft->itemsCount();
        $draft->pcs_count = $draft->itemsCount();
        $draft->base_charge = $draft->getCharge();
        $draft->subtotal = $draft->getCharge();
        $draft->total = $draft->getCharge();
        return $draft;
    }

    public function updateCartItem($draftKey, $itemKey, $newItemData)
    {
        $content = $this->getContent();
        $content[$draftKey]->updateItem($itemKey, $newItemData);
        $content[$draftKey] = $this->updateCart($content[$draftKey]);

        $this->session->put($this->instance, $content);
    }

    public function removeItemFromCart($draftKey, $itemKey)
    {
        $content = $this->getContent();
        $content[$draftKey]->removeItem($itemKey);

        $this->session->put($this->instance, $content);
    }

    public function count()
    {
        return $this->getContent()->count();
    }

    public function isEmpty()
    {
        return $this->count() == 0;
    }

    public function hasContent()
    {
        return !$this->isEmpty();
    }

}