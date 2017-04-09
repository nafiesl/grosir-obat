<?php

namespace Tests\Unit\Integration;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductTest extends TestCase
{
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
}
