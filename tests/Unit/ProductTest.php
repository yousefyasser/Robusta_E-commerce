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
        $newProduct = Product::factory()->make()->toArray();

        $this->login('admin');
        $this->post('/products/create', $newProduct)->assertStatus(200);
    }
}
