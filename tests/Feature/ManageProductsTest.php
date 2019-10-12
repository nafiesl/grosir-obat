<?php

namespace Tests\Feature;

use App\Unit;
use App\Product;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageProductsTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_paginated_product_list_in_product_index_page()
    {
        $product1 = factory(Product::class)->create(['name' => 'Testing 123']);
        $product2 = factory(Product::class)->create(['name' => 'Testing 456']);

        $this->loginAsUser();
        $this->visit(route('products.index'));
        $this->see($product1->name);
        $this->see($product2->name);
    }

    /** @test */
    public function user_can_search_product_by_keyword()
    {
        $this->loginAsUser();
        $product1 = factory(Product::class)->create(['name' => 'Testing 123']);
        $product2 = factory(Product::class)->create(['name' => 'Testing 456']);

        $this->visit(route('products.index'));
        $this->submitForm(trans('product.search'), ['q' => '123']);
        $this->seePageIs(route('products.index', ['q' => 123]));

        $this->see($product1->name);
        $this->dontSee($product2->name);
    }

    /** @test */
    public function user_can_create_a_product()
    {
        $unit = factory(Unit::class)->create(['name' => 'Testing 123']);
        $this->loginAsUser();
        $this->visit(route('products.index'));

        $this->click(trans('product.create'));
        $this->seePageIs(route('products.index', ['action' => 'create']));

        $this->type('Product 1', 'name');
        $this->type('1000', 'cash_price');
        $this->type('1200', 'credit_price');
        $this->type($unit->id, 'unit_id');
        $this->press(trans('product.create'));

        $this->seePageIs(route('products.index'));
        // $this->see(trans('product.created'));

        $this->seeInDatabase('products', [
            'name'         => 'Product 1',
            'cash_price'   => 1000,
            'credit_price' => 1200,
        ]);
    }

    /** @test */
    public function user_can_edit_a_product_within_search_query()
    {
        $unit = factory(Unit::class)->create(['name' => 'Testing 123']);
        $this->loginAsUser();
        $product = factory(Product::class)->create(['name' => 'Testing 123']);

        $this->visit(route('products.index', ['q' => '123']));
        $this->click('edit-product-'.$product->id);
        $this->seePageIs(route('products.index', ['action' => 'edit', 'id' => $product->id, 'q' => '123']));

        $this->type('Product 1', 'name');
        $this->type('1000', 'cash_price');
        $this->type('1200', 'credit_price');
        $this->type($unit->id, 'unit_id');
        $this->press(trans('product.update'));

        $this->seePageIs(route('products.index', ['q' => '123']));

        $this->seeInDatabase('products', [
            'name'         => 'Product 1',
            'cash_price'   => 1000,
            'credit_price' => 1200,
        ]);
    }

    /** @test */
    public function user_can_create_a_product_with_only_cash_price()
    {
        $unit = factory(Unit::class)->create(['name' => 'Testing 123']);
        $this->loginAsUser();
        $this->visit(route('products.index'));

        $this->click(trans('product.create'));
        $this->seePageIs(route('products.index', ['action' => 'create']));

        $this->type('Product 1', 'name');
        $this->type('1000', 'cash_price');
        $this->type('', 'credit_price');
        $this->type($unit->id, 'unit_id');
        $this->press(trans('product.create'));

        $this->seePageIs(route('products.index'));
        // $this->see(trans('product.created'));

        $this->seeInDatabase('products', [
            'name'         => 'Product 1',
            'cash_price'   => 1000,
            'credit_price' => null,
        ]);
    }

    /** @test */
    public function user_can_edit_a_product()
    {
        $unit = factory(Unit::class)->create(['name' => 'Testing 123']);
        $this->loginAsUser();
        $product = factory(Product::class)->create();

        $this->visit(route('products.index'));
        $this->click('edit-product-'.$product->id);
        $this->seePageIs(route('products.index', ['action' => 'edit', 'id' => $product->id]));

        $this->type('Product 1', 'name');
        $this->type('1000', 'cash_price');
        $this->type('1200', 'credit_price');
        $this->type($unit->id, 'unit_id');
        $this->press(trans('product.update'));

        $this->seeInDatabase('products', [
            'name'         => 'Product 1',
            'cash_price'   => 1000,
            'credit_price' => 1200,
        ]);
    }

    /** @test */
    public function user_can_delete_a_product()
    {
        $this->loginAsUser();
        $product = factory(Product::class)->create();

        $this->visit(route('products.index'));
        $this->click('del-product-'.$product->id);
        $this->seePageIs(route('products.index', ['action' => 'delete', 'id' => $product->id]));

        $this->seeInDatabase('products', [
            'id' => $product->id,
        ]);

        $this->press(trans('app.delete_confirm_button'));

        $this->dontSeeInDatabase('products', [
            'id' => $product->id,
        ]);
    }

    /** @test */
    public function user_can_delete_a_product_within_search_query()
    {
        $this->loginAsUser();
        $product = factory(Product::class)->create(['name' => 'Product 123']);

        $this->visit(route('products.index', ['q' => '123']));
        $this->click('del-product-'.$product->id);

        $this->seePageIs(route('products.index', ['action' => 'delete', 'id' => $product->id, 'q' => '123']));
        $this->seeInDatabase('products', [
            'id' => $product->id,
        ]);

        $this->press(trans('app.delete_confirm_button'));

        $this->seePageIs(route('products.index', ['q' => '123']));
        $this->dontSeeInDatabase('products', [
            'id' => $product->id,
        ]);
    }
}
