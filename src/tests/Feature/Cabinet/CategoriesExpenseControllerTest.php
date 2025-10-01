<?php

namespace Tests\Feature\Cabinet;

use App\Models\CategoriesExpense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesExpenseControllerTest extends TestCase
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
        $response = $this->get('/categoriesExpense');
        $response->assertStatus(200);
    }

    public function test_store_creates_category()
    {
        $response = $this->post('/categoriesExpense', [
            'name' => 'Тестовая категория',
        ]);

        $response->assertRedirect();

        $this->assertTrue(
            CategoriesExpense::where('name', 'Тестовая категория')
                ->where('user_id', $this->user->id)
                ->exists()
        );
    }

    public function test_update_changes_category()
    {
        $category = CategoriesExpense::factory()->create(['user_id' => $this->user->id]);

        $response = $this->put("/categoriesExpense/{$category->id}", [
            'name' => 'Изменено',
        ]);

        $response->assertRedirect();

        $this->assertTrue(
            CategoriesExpense::where('id', $category->id)
                ->where('name', 'Изменено')
                ->exists()
        );
    }

    public function test_destroy_deletes_category()
    {
        $category = CategoriesExpense::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/categoriesExpense/{$category->id}");

        $response->assertRedirect();

        $this->assertFalse(
            CategoriesExpense::where('id', $category->id)->exists()
        );
    }
}
