<?php

namespace Tests\Unit\Models;

use App\Unit;
use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_get_price_method()
    {
        $product = new Product(['cash_price' => 3000]);

        $this->assertEquals(3000, $product->getPrice());
    }

    /** @test */
    public function product_get_price_method_default_to_cash_price()
    {
        $product = new Product(['cash_price' => 3000]);

        $this->assertEquals($product->cash_price, $product->getPrice());
    }

    /** @test */
    public function product_get_price_can_also_returns_credit_price_of_product()
    {
        $product = new Product(['cash_price' => 2000, 'credit_price' => 3000]);

        $this->assertEquals($product->credit_price, $product->getPrice('credit'));
        $this->assertEquals(3000, $product->getPrice('credit'));
    }

    /** @test */
    public function product_get_price_returns_cash_price_if_credit_price_is_0_or_null()
    {
        $product = new Product(['cash_price' => 2000, 'credit_price' => 0]);
        $this->assertEquals(2000, $product->getPrice('credit'));

        $product = new Product(['cash_price' => 2000, 'credit_price' => null]);
        $this->assertEquals(2000, $product->getPrice('credit'));
    }

    /** @test */
    public function a_product_has_belongs_to_unit_relation()
    {
        $product = factory(Product::class)->make();

        $this->assertInstanceOf(Unit::class, $product->unit);
        $this->assertEquals($product->unit_id, $product->unit->id);
    }
}
