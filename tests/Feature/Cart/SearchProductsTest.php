<?php

namespace Tests\Feature\Cart;

use App\Product;
use Tests\TestCase;
use App\Cart\CreditDraft;
use App\Cart\CartCollection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchProductsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function retrieving_product_list_by_ajax_post_request()
    {
        // $this->disableExceptionHandling();
        factory(Product::class)->create(['name' => 'Hemaviton']);
        factory(Product::class)->create(['name' => 'Zee']);
        $product1 = factory(Product::class)->create(['name' => 'Bisolvon 1', 'cash_price' => 2000, 'credit_price' => 2100]);
        $product2 = factory(Product::class)->create(['name' => 'Bisolvon 2', 'cash_price' => 3000, 'credit_price' => 3200]);

        $cart = new CartCollection();
        $draft = new CreditDraft();
        $cart->add($draft);

        $user = $this->loginAsUser();

        $response = $this->post(route('api.products.search'), [
            'query'     => 'Bis',
            'draftType' => $draft->type,
            'draftKey'  => $draft->draftKey,
        ]);

        $response->assertSuccessful();
        $response->assertSee($product1->name);
        $response->assertSee(route('cart.add-draft-item', [$draft->draftKey, $product1->id]));
        $response->assertSee($product2->name);
        $response->assertSee(route('cart.add-draft-item', [$draft->draftKey, $product2->id]));
    }
}
