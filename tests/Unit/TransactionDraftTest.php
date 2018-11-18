<?php

namespace Tests\Unit;

use App\Product;
use App\Cart\Item;
use Tests\TestCase;
use App\Cart\CashDraft;
use App\Cart\CartCollection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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

    /** @test */
    public function it_has_destroy_method()
    {
        $cart = new CartCollection();
        $draft = $cart->add(new CashDraft());
        $draftKey = $draft->draftKey;
        $this->assertNotNull($draft);
        $draft->destroy();
        $this->assertNull($cart->get($draftKey));
    }

    /** @test */
    public function it_has_get_total_method()
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

        $this->assertEquals(6000, $draft->getTotal());
    }

    /** @test */
    public function it_has_get_discount_total_method()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());

        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $product2 = factory(Product::class)->create(['cash_price' => 2000]);
        $item1 = new Item($product1, 2);
        $item2 = new Item($product2, 2);

        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $this->assertEquals(0, $draft->getDiscountTotal());
    }

    /** @test */
    public function it_has_get_total_item_qty_method()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());

        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $product2 = factory(Product::class)->create(['cash_price' => 2000]);
        $item1 = new Item($product1, 1);
        $item2 = new Item($product2, 3);

        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $this->assertEquals(4, $draft->getTotalQty());
    }

    /** @test */
    public function draft_item_has_set_item_discount_method()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());

        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $product2 = factory(Product::class)->create(['cash_price' => 2000]);
        $item1 = new Item($product1, 1);
        $item2 = new Item($product2, 3);

        $item2->setItemDiscount(100);

        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $this->assertEquals(6700, $draft->getTotal());
    }

    /** @test */
    public function it_has_get_subtotal_method()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());

        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $product2 = factory(Product::class)->create(['cash_price' => 2000]);
        $item1 = new Item($product1, 1);
        $item2 = new Item($product2, 3);

        $item2->setItemDiscount(100);

        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $this->assertEquals(7000, $draft->getSubtotal());
        $this->assertEquals(6700, $draft->getTotal());
    }

    /** @test */
    public function it_has_payment_and_exchange()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());

        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $product2 = factory(Product::class)->create(['cash_price' => 2000]);
        $item1 = new Item($product1, 1);
        $item2 = new Item($product2, 3);
        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $draftAttributes = [
            'customer' => [
                'name'  => 'Nafies',
                'phone' => '081234567890',
            ],
            'payment'  => 10000,
            'notes'    => 'Catatan',
        ];
        $cart->updateDraftAttributes($draft->draftKey, $draftAttributes);

        $this->assertEquals(10000, $draft->payment);
        $this->assertEquals(7000, $draft->getTotal());
        $this->assertEquals(3000, $draft->getExchange());
        $this->assertEquals([
            'name'  => 'Nafies',
            'phone' => '081234567890',
        ], $draft->customer);
        $this->assertEquals('Catatan', $draft->notes);
    }

    /** @test */
    public function it_has_store_method_to_save_to_database()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());

        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $product2 = factory(Product::class)->create(['cash_price' => 2000]);
        $item1 = new Item($product1, 1);
        $item2 = new Item($product2, 3);
        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $draftAttributes = [
            'customer' => [
                'name'  => 'Nafies',
                'phone' => '081234567890',
            ],
            'payment'  => 10000,
            'notes'    => 'Catatan',
        ];
        $cart->updateDraftAttributes($draft->draftKey, $draftAttributes);

        $draft->store();

        $this->assertDatabaseHas('transactions', [
            'invoice_no' => date('ym').'0001',
            'items'      => '[{"id":'.$product1->id.',"name":"'.$product1->name.'","unit":"'.$product1->unit->name.'","price":1000,"qty":1,"item_discount":0,"item_discount_subtotal":0,"subtotal":1000},{"id":'.$product2->id.',"name":"'.$product2->name.'","unit":"'.$product2->unit->name.'","price":2000,"qty":3,"item_discount":0,"item_discount_subtotal":0,"subtotal":6000}]',
            'customer'   => '{"name":"Nafies","phone":"081234567890"}',
            'payment'    => 10000,
            'total'      => 7000,
            'notes'      => 'Catatan',
            'user_id'    => 1,
        ]);
    }

    /** @test */
    public function it_has_product_search_method()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $count = 2;
        $product1 = factory(Product::class)->create(['cash_price' => 1000]);
        $item1 = new Item($product1, $count);

        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);

        $this->assertEquals($draft->search($product1)->id, $product1->id);
    }

    /** @test */
    public function it_has_search_item_key_for_product_method()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());
        $count = 2;

        $product1 = factory(Product::class)->create();
        $item1 = new Item($product1, $count);
        $cart->addItemToDraft($draft->draftKey, $item1);

        $product2 = factory(Product::class)->create();
        $item2 = new Item($product2, $count);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $product3 = factory(Product::class)->create();
        $item3 = new Item($product3, $count);
        $cart->addItemToDraft($draft->draftKey, $item3);

        $this->assertEquals($draft->searchItemKeyFor($product3), 2);
        $this->assertEquals($draft->searchItemKeyFor($product2), 1);
        $this->assertEquals($draft->searchItemKeyFor($product1), 0);
    }
}
