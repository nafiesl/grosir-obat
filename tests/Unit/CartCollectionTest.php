<?php

namespace Tests\Unit;

use App\Cart\CartCollection;
use App\Cart\CashDraft;
use App\Cart\CreditDraft;
use Tests\TestCase;

class CartCollectionTest extends TestCase
{
    /** @test */
    public function it_has_a_default_instance()
    {
        $cart = new CartCollection;
        $this->assertEquals('drafts', $cart->currentInstance());
    }

    /** @test */
    public function it_can_have_multiple_instances()
    {
        $cart = new CartCollection;

        $cashDraft = new CashDraft;
        $creditDraft = new CreditDraft;

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $cart->instance('wishlist')->add($cashDraft);
        $cart->instance('wishlist')->add($creditDraft);

        $this->assertCount(2, $cart->instance('drafts')->content());
        $this->assertCount(2, $cart->instance('wishlist')->content());
    }

    /** @test */
    public function cart_collection_consist_of_transacion_draft_class()
    {
        $cart = new CartCollection;
        $cashDraft = new CashDraft;
        $creditDraft = new CreditDraft;

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $this->assertCount(2, $cart->content());
        $this->assertTrue($cart->hasContent());
    }
}
