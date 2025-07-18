<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CategoriesIncome;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesIncomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_ok()
    {
        $response = $this->get('/categoriesIncome');
        $response->assertStatus(200);
    }

    public function test_store_creates_category()
    {
        $response = $this->post('/categoriesIncome', [
            'name' => 'Тест доход',
        ]);

        $response->assertRedirect();
        $this->assertTrue(
            CategoriesIncome::where('name', 'Тест доход')
                ->where('user_id', $this->user->id)
                ->exists()
        );
    }

    public function test_update_changes_category()
    {
        $category = CategoriesIncome::factory()->create(['user_id' => $this->user->id]);

        $response = $this->put("/categoriesIncome/{$category->id}", [
            'name' => 'Изменено',
        ]);

        $response->assertRedirect();
        $this->assertTrue(
            CategoriesIncome::where('id', $category->id)
                ->where('name', 'Изменено')
                ->exists()
        );
    }

    public function test_destroy_deletes_category()
    {
        $category = CategoriesIncome::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/categoriesIncome/{$category->id}");

        $response->assertRedirect();
        $this->assertFalse(
            CategoriesIncome::where('id', $category->id)->exists()
        );
    }
}
