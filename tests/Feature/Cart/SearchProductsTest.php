<?php

namespace Tests\Feature\Cart;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SearchProductsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function retrieving_product_list_by_ajax_post_request()
    {
        $this->disableExceptionHandling();
        factory(Product::class)->create(['name' => 'Hemaviton']);
        factory(Product::class)->create(['name' => 'Zee']);
        $product1 = factory(Product::class)->create(['name' => 'Bisolvon 1']);
        $product2 = factory(Product::class)->create(['name' => 'Bisolvon 2']);

        $user = $this->loginAsUser();

        $response = $this->post(route('api.products.search'), ['query'=> 'Bis']);

        $response->assertSuccessful();

        $response->assertJsonFragment([
            'name' => 'Bisolvon 1',
            'name' => 'Bisolvon 2',
        ]);
    }
}
