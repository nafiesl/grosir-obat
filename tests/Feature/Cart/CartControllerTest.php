<?php

namespace Tests\Feature\Cart;

use App\Cart\CartCollection;
use App\Cart\CashDraft;
use App\Cart\CreditDraft;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_add_new_draft_into_cart()
    {
        $this->loginAsUser();

        $cart = new CartCollection;

        $response = $this->post(route('cart.add', 1));
        $response = $this->post(route('cart.add', 2));
        $response->assertSessionHas('transactions.drafts');

        $cashDraft = $cart->content()->first();
        $this->assertTrue($cashDraft instanceof CashDraft);

        $creditDraft = $cart->content()->last();
        $this->assertTrue($creditDraft instanceof CreditDraft);
    }

    /** @test */
    public function user_can_add_item_product_into_cart_draft()
    {
        $this->loginAsUser();

        $cart = new CartCollection;
        $draft = new CashDraft;
        $cart->add($draft);

        // Add Product to database
        $product = factory(Product::class)->create(['cash_price' => 1100], ['credit_price' => 1000]);
        $itemQty = 2;

        // Add Product as CashDraft item
        $response = $this->post(route('cart.add-draft-item', [$draft->draftKey, $product->id]), [
            'qty' => $itemQty
        ]);

        $cashDraft = $cart->content()->first();
        $this->assertTrue($cashDraft instanceof CashDraft);
        $this->assertEquals(2200, $cashDraft->getTotal());
    }
}