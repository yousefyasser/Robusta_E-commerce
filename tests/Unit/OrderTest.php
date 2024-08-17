<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    use MakesGraphQLRequests;

    public function add_to_cart_mutation_response(int $product_id, int $quantity): \Illuminate\Testing\TestResponse
    {
        return $this->graphQL('
            mutation ($product_id: ID!, $quantity: Int!) {
                addToCart(product_id: $product_id, quantity: $quantity) {
                    id
                    name
                    price
                }
            }
        ', [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ]);
    }

    public function test_add_to_cart_without_authentication()
    {
        $product = Product::factory()->create();

        $response = $this->add_to_cart_mutation_response($product->id, $product->stock);
        $response->assertGraphQLErrorMessage('Unauthenticated.');
    }

    public function test_add_to_cart_with_invalidInput()
    {
        $response = $this->add_to_cart_mutation_response(0, -1);
        $response->assertGraphQLErrorMessage('Validation failed for the field [addToCart].');
    }

    public function test_add_to_cart_successfully()
    {
        $this->login('user');

        $product = Product::factory()->create();

        $response = $this->add_to_cart_mutation_response($product->id, $product->stock);
        $response->assertGraphQLErrorFree();

        $this->assertDatabaseHas('shopping_carts', [
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'quantity' => $product->stock,
        ]);
    }
}
