<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use MakesGraphQLRequests;

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

    public function test_get_products()
    {
        $this->seed();

        $this->get_products_query_response()->assertGraphQLErrorFree();
    }

    public function test_filter_products_by_category_id()
    {
        $this->seed();
        $categoryId = Category::all()->whereNotNull('parent_id')->first()->id;

        $response = $this->get_products_query_response(category: $categoryId)->assertGraphQLErrorFree();

        foreach ($response->json('data.products.data') as $product) {
            $this->assertEquals($categoryId, $product['category_id']);
        }
    }

    public function test_filter_products_by_name()
    {
        $this->seed();
        $search = Product::all()->random()->name;

        $response = $this->get_products_query_response(search: $search)->assertGraphQLErrorFree();

        foreach ($response->json('data.products.data') as $product) {
            $this->assertStringContainsStringIgnoringCase($search, $product['name']);
        }
    }

    public function test_sort_products()
    {
        $this->seed();

        $response = $this->get_products_query_response(sortBy: 'PRICE_DESC')->assertGraphQLErrorFree();

        $products = $response->json('data.products.data');
        $sortedPrices = $prices = array_column($products, 'price');
        rsort($sortedPrices);

        $this->assertEquals($sortedPrices, $prices);
    }

    public function get_products_query_response(int $category = null, string $search = null, string $sortBy = 'CREATED_AT_ASC'): \Illuminate\Testing\TestResponse
    {
        return $this->graphQL('
            query ($category: ID, $search: String, $sortBy: SortCriteria!, $page: Int!, $first: Int!) {
                products(category: $category, search: $search, sortBy: $sortBy, page: $page, first: $first) {
                    data {
                        name
                        category_id
                        price
                        created_at
                    }
                }
            }
        ', [
            'category' => $category,
            'search' => $search,
            'sortBy' => $sortBy,
            'page' => 1,
            'first' => 10,
        ]);
    }
}
