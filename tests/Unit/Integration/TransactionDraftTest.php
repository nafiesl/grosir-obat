<?php

namespace Tests\Unit\Integration;

use App\Cart\CartCollection;
use App\Cart\CashDraft;
use App\Cart\Item;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TransactionDraftTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_found_an_item_in_a_draft()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $count = 2;
        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $product2 = factory(Product::class)->create(['cash_price' => 2000]);
        $item1 = new Item($product1, $count);
        $item2 = new Item($product2, $count);

        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $this->assertTrue($cart->draftHasItem($draft, $product1));
        $this->assertTrue($cart->draftHasItem($draft, $product2));
        $this->assertEquals(6000, $draft->getTotal());

        // Remove an item from draft
        $cart->removeItemFromDraft($draft->draftKey, 1);
        $this->assertFalse($cart->draftHasItem($draft, $product2));

        $this->assertEquals(2000, $draft->getTotal());
    }
}
