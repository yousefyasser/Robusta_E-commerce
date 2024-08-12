<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function login($role = 'user')
    {
        $user = User::factory()->create([
            'role' => $role,
        ]);

        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    }

    public function test_create_category_unauthorized()
    {
        $categoryData = Category::factory()->make()->toArray();

        $this->postJson('/categories/create', $categoryData)->assertStatus(401);

        $this->login('user');
        $this->postJson('/categories/create', $categoryData)->assertStatus(401);
    }

    public function test_create_category_validation()
    {
        $this->login('admin');

        $categoryData = Category::factory()->make(['parent_id' => 1])->toArray();

        $this->postJson('/categories/create', [])->assertStatus(422);

        $this->postJson('/categories/create', $categoryData)->assertStatus(422);
    }

    public function test_create_category_successfully()
    {
        $this->login('admin');

        $categoryData = Category::factory()->make()->toArray();

        $this->postJson('/categories/create', $categoryData)->assertStatus(200);

        $this->assertCount(1, Category::all());
    }
}
