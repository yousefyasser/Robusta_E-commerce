<?php

namespace Tests\Unit;

use App\Models\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_product_unauthorized()
    {
        $this->seed();
        $newProduct = Product::factory()->make()->toArray();

        $this->post('/products/create', $newProduct)->assertStatus(401);

        $this->login('user');
        $this->post('/products/create', $newProduct)->assertStatus(401);
    }

    public function test_create_product_validation()
    {
        $this->login('admin');
        $this->post('/products/create', [])->assertStatus(422);
    }

    public function test_create_product_successfully()
    {
        $this->seed();
        $prevProductCount = Product::count();
        $newProduct = Product::factory()->make()->toArray();

        $this->login('admin');
        $this->post('/products/create', $newProduct)->assertStatus(200);
        $this->assertCount($prevProductCount + 1, Product::all());
    }
}
