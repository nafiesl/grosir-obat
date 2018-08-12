<?php

namespace Tests\Feature\Cart;

use App\Product;
use App\Cart\Item;
use Tests\TestCase;
use App\Cart\CashDraft;
use App\Cart\CreditDraft;
use App\Cart\CartCollection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CartControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_add_new_draft_into_cart()
    {
        $this->loginAsUser();

        $cart = new CartCollection();

        $response = $this->post(route('cart.add'), ['create-cash-draft' => trans('transaction.create_cash')]);
        $response = $this->post(route('cart.add'), ['create-credit-draft' => trans('transaction.create_credit')]);
        $response->assertSessionHas('transactions.drafts');

        $cashDraft = $cart->content()->first();
        $this->assertTrue($cashDraft instanceof CashDraft);

        $creditDraft = $cart->content()->last();
        $this->assertTrue($creditDraft instanceof CreditDraft);
    }

    /** @test */
    public function user_can_add_item_product_into_cash_draft()
    {
        $this->loginAsUser();

        $cart = new CartCollection();
        $draft = new CashDraft();
        $cart->add($draft);

        // Add Product to database
        $product = factory(Product::class)->create(['cash_price' => 1100], ['credit_price' => 1000]);
        $itemQty = 2;

        // Add Product as CashDraft item
        $response = $this->post(route('cart.add-draft-item', [$draft->draftKey, $product->id]), [
            'qty' => $itemQty,
        ]);

        $cashDraft = $cart->content()->first();
        $this->assertTrue($cashDraft instanceof CashDraft);
        $this->assertEquals(2200, $cashDraft->getTotal());
    }

    /** @test */
    public function user_can_add_item_product_into_credit_draft()
    {
        $this->loginAsUser();

        $cart = new CartCollection();
        $draft = new CreditDraft();
        $cart->add($draft);

        // Add Product to database
        $product = factory(Product::class)->create(['cash_price' => 1100], ['credit_price' => 1000]);
        $itemQty = 2;

        // Add Product as CreditDraft item
        $response = $this->post(route('cart.add-draft-item', [$draft->draftKey, $product->id]), [
            'qty' => $itemQty,
        ]);
        // $response->assertStatus(200);

        $cashDraft = $cart->content()->first();
        $this->assertTrue($cashDraft instanceof CreditDraft);
        $this->assertEquals(2000, $cashDraft->getTotal());
    }

    /** @test */
    public function user_can_remove_item_product_from_a_transaction_draft()
    {
        $this->loginAsUser();

        $cart = new CartCollection();
        $cashDraft = new CashDraft();
        $product = factory(Product::class)->create(['cash_price' => 1100], ['credit_price' => 1000]);
        $item = new Item($product, 2);

        $cashDraft->addItem($item);
        $cart->add($cashDraft);

        $this->assertEquals(2, $cashDraft->getTotalQty());
        $this->assertEquals(2200, $cashDraft->getTotal());

        // Remove Item Product from CashDraft
        $response = $this->delete(route('cart.remove-draft-item', [$cashDraft->draftKey]), [
            'item_index' => 0,
        ]);

        $this->assertEquals(0, $cashDraft->getTotalQty());
        $this->assertCount(0, $cashDraft->items());
        $this->assertEquals(0, $cashDraft->getTotal());
    }

    /** @test */
    public function user_can_remove_a_transaction_draft_from_cart()
    {
        $this->loginAsUser();

        $cart = new CartCollection();
        $cashDraft = new CashDraft();
        $cart->add($cashDraft);

        $this->assertFalse($cart->isEmpty());
        $this->assertEquals(1, $cart->count());

        // Remove a transaction draft
        $response = $this->delete(route('cart.remove'), [
            'draft_key' => $cashDraft->draftKey,
        ]);

        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    public function user_can_destroy_cart()
    {
        $this->loginAsUser();

        $cart = new CartCollection();
        $cashDraft = new CashDraft();
        $cart->add($cashDraft);
        $cart->add($cashDraft);

        $this->assertFalse($cart->isEmpty());
        $this->assertEquals(2, $cart->count());

        // Destroy cart (empty out cart)
        $response = $this->delete(route('cart.destroy'));

        $this->assertTrue($cart->isEmpty());
    }

    /** @test */
    public function user_can_empty_out_transaction_draft()
    {
        $this->loginAsUser();

        $cart = new CartCollection();
        $cashDraft = new CashDraft();
        $product = factory(Product::class)->create(['cash_price' => 1100], ['credit_price' => 1000]);
        $item = new Item($product, 2);

        $cashDraft->addItem($item);
        $cart->add($cashDraft);

        $this->assertEquals(2, $cashDraft->getTotalQty());
        $this->assertEquals(2200, $cashDraft->getTotal());

        // Empty out a transaction draft
        $response = $this->delete(route('cart.empty', $cashDraft->draftKey));

        $this->assertEquals(0, $cashDraft->getTotalQty());
        $this->assertCount(0, $cashDraft->items());
        $this->assertEquals(0, $cashDraft->getTotal());
    }

    /** @test */
    public function user_can_update_draft_item_qty_and_item_discount_in_a_draft()
    {
        $this->loginAsUser();

        $cart = new CartCollection();
        $cashDraft = new CashDraft();
        $product = factory(Product::class)->create(['cash_price' => 1100], ['credit_price' => 1000]);
        $item = new Item($product, 2);

        $cashDraft->addItem($item);
        $cart->add($cashDraft);

        $this->assertEquals(2, $cashDraft->getTotalQty());
        $this->assertEquals(2200, $cashDraft->getTotal());

        // Update draft item on a transaction draft
        $response = $this->patch(route('cart.update-draft-item', [$cashDraft->draftKey]), [
            'item_key'      => 0,
            'qty'           => 3,
            'item_discount' => 100,
        ]);

        $this->assertEquals(3, $cashDraft->getTotalQty());
        $this->assertEquals(3000, $cashDraft->getTotal());
        $this->assertEquals(300, $cashDraft->getDiscountTotal());
    }
}
