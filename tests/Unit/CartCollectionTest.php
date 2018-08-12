<?php

namespace Tests\Unit;

use App\Product;
use App\Cart\Item;
use Tests\TestCase;
use App\Cart\CashDraft;
use App\Cart\CreditDraft;
use App\Cart\CartCollection;

class CartCollectionTest extends TestCase
{
    /** @test */
    public function it_has_a_default_instance()
    {
        $cart = new CartCollection();
        $this->assertEquals('drafts', $cart->currentInstance());
    }

    /** @test */
    public function it_can_have_multiple_instances()
    {
        $cart = new CartCollection();

        $cashDraft = new CashDraft();
        $creditDraft = new CreditDraft();

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $cart->instance('wishlist')->add($cashDraft);
        $cart->instance('wishlist')->add($creditDraft);

        $this->assertCount(2, $cart->instance('drafts')->content());
        $this->assertCount(2, $cart->instance('wishlist')->content());
    }

    /** @test */
    public function cart_collection_consist_of_transacion_draft()
    {
        $cart = new CartCollection();
        $cashDraft = new CashDraft();
        $creditDraft = new CreditDraft();

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $this->assertCount(2, $cart->content());
        $this->assertTrue($cart->hasContent());
    }

    /** @test */
    public function it_can_get_a_draft_by_key()
    {
        $draft = new CashDraft();
        $cart = new CartCollection();

        $cart->add($draft);
        $gottenDraft = $cart->get($draft->draftKey);
        $invalidDraft = $cart->get('random_key');

        $this->assertEquals($draft, $gottenDraft);
        $this->assertNull($invalidDraft);
    }

    /** @test */
    public function it_can_remove_draft_from_draft_collection()
    {
        $cart = new CartCollection();
        $cashDraft = new CashDraft();
        $creditDraft = new CreditDraft();

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $this->assertCount(2, $cart->content());
        $cart->removeDraft($cart->content()->keys()->last());
        $this->assertCount(1, $cart->content());
    }

    /** @test */
    public function it_can_be_empty_out()
    {
        $cart = new CartCollection();

        $cashDraft = new CashDraft();
        $creditDraft = new CreditDraft();

        $cart->add($cashDraft);
        $cart->add($cashDraft);
        $cart->add($cashDraft);
        $cart->add($creditDraft);
        $cart->add($creditDraft);

        $this->assertCount(5, $cart->content());
        $cart->destroy();

        $this->assertCount(0, $cart->content());
        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    public function it_has_content_keys()
    {
        $cart = new CartCollection();

        $cashDraft = new CashDraft();
        $creditDraft = new CreditDraft();

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $this->assertCount(2, $cart->keys());
        $cart->removeDraft($cart->content()->keys()->last());
        $this->assertCount(1, $cart->keys());
    }

    /** @test */
    public function it_can_update_a_draft_attributes()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $this->assertCount(1, $cart->content());

        $newDraftAttribute = [
            'invoice_no' => 2,
            'date'       => 1,
            'items'      => [],
            'total'      => 0,
            'payment'    => 0,
            'customer'   => 0,
            'status_id'  => 0,
            'creator_id' => 0,
            'remark'     => 0,
        ];

        $cart->updateDraftAttributes($draft->draftKey, $newDraftAttribute);
        $this->assertArrayHasKey('invoice_no', $draft->toArray());
        $this->assertArrayHasKey('date', $draft->toArray());
        $this->assertArrayHasKey('items', $draft->toArray());
        $this->assertArrayHasKey('total', $draft->toArray());
        $this->assertArrayHasKey('payment', $draft->toArray());
        $this->assertArrayHasKey('customer', $draft->toArray());
        $this->assertArrayHasKey('status_id', $draft->toArray());
        $this->assertArrayHasKey('creator_id', $draft->toArray());
        $this->assertArrayHasKey('remark', $draft->toArray());
    }

    /** @test */
    public function it_can_add_product_to_draft()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $count = 2;
        $item = new Item(new Product(['cash_price' => 1000]), $count);

        $cart->addItemToDraft($draft->draftKey, $item);
        $this->assertEquals(2000, $draft->getTotal());
        $this->assertEquals(1, $draft->getItemsCount());
        $this->assertEquals(2, $draft->getTotalQty());
    }

    /** @test */
    public function it_adds_product_qty_to_draft_if_product_id_exists()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $count = 2;

        $item = new Item(new Product(['id' => 1, 'cash_price' => 1000]), $count);
        $cart->addItemToDraft($draft->draftKey, $item);

        $item = new Item(new Product(['id' => 1, 'cash_price' => 1000]), $count);
        $cart->addItemToDraft($draft->draftKey, $item);

        $item = new Item(new Product(['id' => 2, 'cash_price' => 1000]), $count);
        $cart->addItemToDraft($draft->draftKey, $item);

        $this->assertEquals(6000, $draft->getTotal());
        $this->assertEquals(2, $draft->getItemsCount());
        $this->assertEquals(6, $draft->getTotalQty());
    }

    /** @test */
    public function it_can_remove_item_from_draft()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $item = new Item(new Product(['cash_price' => 1000]), 3);

        $cart->addItemToDraft($draft->draftKey, $item);
        $this->assertCount(1, $draft->items());
        $cart->removeItemFromDraft($draft->draftKey, 0);
        $this->assertCount(0, $draft->items());
        $this->assertEquals(0, $draft->getTotal());
    }

    /** @test */
    public function it_can_update_an_item_of_draft()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $item = new Item(new Product(['cash_price' => 1100]), 3);

        $cart->addItemToDraft($draft->draftKey, $item);
        $this->assertCount(1, $draft->items());
        $this->assertEquals(3300, $draft->getTotal());

        $newItemData = [
            'qty'           => 2,
            'item_discount' => 100,
        ];

        $cart->updateDraftItem($draft->draftKey, 0, $newItemData);
        $this->assertEquals(2000, $draft->getTotal());
    }
}
