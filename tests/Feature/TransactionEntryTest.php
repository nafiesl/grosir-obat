<?php

namespace Tests\Feature;

use App\Cart\CartCollection;
use App\Cart\CashDraft;
use App\Cart\CreditDraft;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\BrowserKitTestCase;

class TransactionEntryTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_visit_transaction_drafts_page()
    {
        $this->loginAsUser();

        // Add new draft to collection
        $cart = new CartCollection;
        $draft = $cart->add(new CashDraft);

        $this->visit(route('cart.index'));

        $this->assertViewHas('draft', $draft);
        $this->see($draft->type);
    }

    /** @test */
    public function user_can_create_transaction_draft_by_transaction_create_button()
    {
        $this->loginAsUser();
        $this->visit(route('home'));

        $this->press(trans('transaction.create'));
        $cart = new CartCollection;
        $draft = $cart->content()->last();
        $this->seePageIs(route('cart.show', $draft->draftKey));

        $this->press(trans('transaction.create_credit'));
        $cart = new CartCollection;
        $draft = $cart->content()->last();
        $this->seePageIs(route('cart.show', $draft->draftKey));
    }

    /** @test */
    public function user_can_search_product_on_transaction_draft_page()
    {
        $product = factory(Product::class)->create(['name' => 'Testing Produk 1']);
        $this->loginAsUser();

        $cart = new CartCollection;
        $draft = new CreditDraft;
        $cart->add($draft);

        // Visit cart index page
        $this->visit(route('cart.index'));

        // Visit search for products
        $this->submitForm(trans('product.search'), [
            'query' => 'testing',
        ]);

        $this->seePageIs(route('cart.show', [$draft->draftKey, 'query' => 'testing']));
        // See product list appears
        $this->see($product->name);
        $this->see($product->credit_price);
        $this->dontSee($product->cash_price);
    }
}
