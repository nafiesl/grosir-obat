<?php

namespace Tests\Feature;

use App\Product;
use App\Cart\Item;
use App\Cart\CashDraft;
use App\Cart\CreditDraft;
use App\Cart\CartCollection;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TransactionEntryTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_visit_transaction_drafts_page()
    {
        $this->loginAsUser();

        // Add new draft to collection
        $cart = new CartCollection();
        $draft = $cart->add(new CashDraft());

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
        $cart = new CartCollection();
        $draft = $cart->content()->last();
        $this->seePageIs(route('cart.show', $draft->draftKey));

        $this->press(trans('transaction.create_credit'));
        $cart = new CartCollection();
        $draft = $cart->content()->last();
        $this->seePageIs(route('cart.show', $draft->draftKey));
    }

    /** @test */
    public function user_can_search_product_on_transaction_draft_page()
    {
        $product = factory(Product::class)->create(['name' => 'Testing Produk 1']);
        $this->loginAsUser();

        $cart = new CartCollection();
        $draft = new CreditDraft();
        $cart->add($draft);

        // Visit cart index page
        $this->visit(route('cart.index'));

        // Visit search for products
        $this->submitForm(trans('cart.product_search'), [
            'query' => 'testing',
        ]);

        $this->seePageIs(route('cart.show', [$draft->draftKey, 'query' => 'testing']));
        // See product list appears
        $this->see($product->name);
        $this->see(format_rp($product->credit_price));
        $this->seeElement('form', ['action' => route('cart.add-draft-item', [$draft->draftKey, $product->id])]);
        $this->seeElement('input', ['id' => 'qty-'.$product->id, 'name' => 'qty']);
        $this->seeElement('input', ['id' => 'add-product-'.$product->id]);
        $this->dontSee($product->cash_price);
    }

    /** @test */
    public function user_can_add_item_to_cash_draft()
    {
        $product1 = factory(Product::class)->create(['name' => 'Testing Produk 1', 'cash_price' => 400, 'credit_price' => 500]);
        $product2 = factory(Product::class)->create(['name' => 'Testing Produk 2', 'cash_price' => 1000, 'credit_price' => 1100]);
        $this->loginAsUser();

        $cart = new CartCollection();
        $draft = new CashDraft();
        $cart->add($draft);

        // Visit cart index with searched item
        $this->visit(route('cart.show', [$draft->draftKey, 'query' => 'testing']));

        $this->type(2, 'qty');
        $this->press('add-product-'.$product1->id);
        // $this->see(trans('cart.item_added', [
        //     'product_name' => $product1->name.' ('.$product1->unit->name.')',
        //     'qty'          => 2,
        // ]));

        $this->type(3, 'qty');
        $this->press('add-product-'.$product2->id);
        // $this->see(trans('cart.item_added', [
        //     'product_name' => $product2->name.' ('.$product2->unit->name.')',
        //     'qty'          => 3,
        // ]));

        $this->seePageIs(route('cart.show', [$draft->draftKey, 'query' => 'testing']));
        $this->assertTrue($cart->draftHasItem($draft, $product1));
        $this->assertTrue($cart->draftHasItem($draft, $product2));
        $this->assertEquals(3800, $draft->getTotal());

        $this->seeElement('input', ['id' => 'qty-'. 0]);
        $this->seeElement('input', ['id' => 'item_discount-'. 0]);
        $this->seeElement('button', ['id' => 'remove-item-'. 0]);
        $this->see(format_rp(3800));
    }

    /** @test */
    public function user_can_add_item_to_credit_draft()
    {
        $product1 = factory(Product::class)->create(['name' => 'Testing Produk 1', 'cash_price' => 400, 'credit_price' => 500]);
        $product2 = factory(Product::class)->create(['name' => 'Testing Produk 2', 'cash_price' => 1000, 'credit_price' => 1100]);
        $this->loginAsUser();

        $cart = new CartCollection();
        $draft = new CreditDraft();
        $cart->add($draft);

        // Visit cart index with searched item
        $this->visit(route('cart.show', [$draft->draftKey, 'query' => 'testing']));

        $this->type(2, 'qty');
        $this->press('add-product-'.$product1->id);
        $this->type(3, 'qty');
        $this->press('add-product-'.$product2->id);

        $this->seePageIs(route('cart.show', [$draft->draftKey, 'query' => 'testing']));
        $this->assertTrue($cart->draftHasItem($draft, $product1));
        $this->assertTrue($cart->draftHasItem($draft, $product2));
        $this->assertEquals(4300, $draft->getTotal());
    }

    /** @test */
    public function user_can_update_item_qty()
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

        $this->loginAsUser();
        $this->visit(route('cart.show', $draft->draftKey));

        $this->submitForm('update-item-0', [
            'item_key'      => 0,
            'qty'           => 2,
            'item_discount' => 100,
        ]);

        $this->submitForm('update-item-1', [
            'item_key'      => 1,
            'qty'           => 2,
            'item_discount' => 100,
        ]);

        $this->assertEquals(400, $draft->getDiscountTotal());
        $this->assertEquals(6000, $draft->getSubtotal());
        $this->assertEquals(5600, $draft->getTotal());

        $this->see(format_rp($draft->getSubtotal()));
        $this->see(format_rp($draft->getTotal()));
    }

    /** @test */
    public function user_can_update_draft_transaction_detail_and_get_confirm_page()
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

        $this->loginAsUser();
        $this->visit(route('cart.show', $draft->draftKey));

        $this->type('Nafies', 'customer[name]');
        $this->type('-', 'customer[phone]');
        $this->type('catatan', 'notes');
        $this->type(10000, 'payment');
        $this->press(trans('transaction.proccess'));

        $this->seePageIs(route('cart.show', [$draft->draftKey, 'action' => 'confirm']));

        $this->see(trans('transaction.confirm'));
        $this->see($draft->customer['name']);
        $this->see($draft->customer['phone']);
        $this->see($draft->notes);
        $this->see(format_rp(10000));
        $this->see(format_rp(3000));
        $this->seeElement('input', ['id' => 'save-transaction-draft']);
    }

    /** @test */
    public function it_validates_proper_payment_amount()
    {
        $cart = new CartCollection();

        $draft = $cart->add(new CashDraft());

        $product1 = factory(Product::class)->create(['cash_price' => 100000]);
        $product2 = factory(Product::class)->create(['cash_price' => 50000]);
        $item1 = new Item($product1, 1);
        $item2 = new Item($product2, 3);

        // Add items to draft
        $cart->addItemToDraft($draft->draftKey, $item1);
        $cart->addItemToDraft($draft->draftKey, $item2);

        $this->loginAsUser();
        $this->visit(route('cart.show', $draft->draftKey));

        $this->type('Nafies', 'customer[name]');
        $this->type('-', 'customer[phone]');
        $this->type('catatan', 'notes');

        $this->type(100000, 'payment');
        $this->press(trans('transaction.proccess'));
        $this->dontSee(trans('transaction.confirm'));

        $this->type(350001, 'payment');
        $this->press(trans('transaction.proccess'));
        $this->dontSee(trans('transaction.confirm'));

        $this->type(350000, 'payment');
        $this->press(trans('transaction.proccess'));
        $this->see(trans('transaction.confirm'));
    }

    /** @test */
    public function user_can_save_transaction_if_draft_is_completed()
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

        $user = $this->loginAsUser();
        $this->visit(route('cart.show', [$draft->draftKey, 'action' => 'confirm']));

        $this->press(trans('transaction.save'));

        $this->seePageIs(route('transactions.show', date('ym').'0001'));
        // $this->see(trans('transaction.created', ['invoice_no' => date('ym').'0001']));

        $this->seeInDatabase('transactions', [
            'invoice_no' => date('ym').'0001',
            'items'      => '[{"id":'.$product1->id.',"name":"'.$product1->name.'","unit":"'.$product1->unit->name.'","price":1000,"qty":1,"item_discount":0,"item_discount_subtotal":0,"subtotal":1000},{"id":'.$product2->id.',"name":"'.$product2->name.'","unit":"'.$product2->unit->name.'","price":2000,"qty":3,"item_discount":0,"item_discount_subtotal":0,"subtotal":6000}]',
            'customer'   => '{"name":"Nafies","phone":"081234567890"}',
            'payment'    => 10000,
            'total'      => 7000,
            'notes'      => 'Catatan',
            'user_id'    => $user->id,
        ]);
    }
}
