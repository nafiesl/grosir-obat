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
    public function cart_collection_consist_of_transacion_draft()
    {
        $cart = new CartCollection;
        $cashDraft = new CashDraft;
        $creditDraft = new CreditDraft;

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $this->assertCount(2, $cart->content());
        $this->assertTrue($cart->hasContent());
    }

    /** @test */
    public function it_can_get_a_draft_by_key()
    {
        $draft = new CashDraft;
        $cart = new CartCollection;

        $cart->add($draft);
        $gottenDraft = $cart->get($draft->draftKey);
        $invalidDraft = $cart->get('random_key');

        $this->assertEquals($draft, $gottenDraft);
        $this->assertNull($invalidDraft);
    }

    /** @test */
    public function it_can_remove_draft_from_draft_collection()
    {
        $cart = new CartCollection;
        $cashDraft = new CashDraft;
        $creditDraft = new CreditDraft;

        $cart->add($cashDraft);
        $cart->add($creditDraft);

        $this->assertCount(2, $cart->content());
        $cart->removeDraft($cart->content()->keys()->last());
        $this->assertCount(1, $cart->content());
    }

    /** @test */
    public function it_can_be_empty_out()
    {
        $cart = new CartCollection;

        $cashDraft = new CashDraft;
        $creditDraft = new CreditDraft;

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
}
